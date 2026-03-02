<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    /**
     * Display backup management page
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin only');
        }

        // Get all backup files
        $backupDir = storage_path('app/backups');
        
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $backups = collect(File::glob($backupDir . '/backup_*.sql.gz'))
            ->map(function ($file) {
                return [
                    'filename' => basename($file),
                    'path' => $file,
                    'size' => File::size($file),
                    'size_human' => $this->formatBytes(File::size($file)),
                    'created_at' => File::lastModified($file),
                    'created_at_human' => date('Y-m-d H:i:s', File::lastModified($file)),
                ];
            })
            ->sortByDesc('created_at')
            ->values();

        return view('admin.backups.index', compact('backups'));
    }

    /**
     * Create a new backup manually
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin only');
        }

        try {
            $this->info('Starting manual backup...');

            // Run the backup command
            Artisan::call('backup:database');
            $output = Artisan::output();

            // Check if backup was successful
            if (str_contains($output, '✅')) {
                Log::info('Manual backup created by admin', [
                    'user_id' => Auth::id(),
                    'user_name' => Auth::user()->name
                ]);

                return redirect()->route('admin.backups.index')
                    ->with('success', 'Backup created successfully!');
            } else {
                Log::error('Manual backup failed', [
                    'output' => $output,
                    'user_id' => Auth::id()
                ]);

                return redirect()->route('admin.backups.index')
                    ->with('error', 'Backup failed. Please check logs for details.');
            }

        } catch (\Exception $e) {
            Log::error('Manual backup exception', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()->route('admin.backups.index')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file
     */
    public function download($filename)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin only');
        }

        // Validate filename format
        if (!preg_match('/^backup_\d{4}-\d{2}-\d{2}_\d{6}\.sql\.gz$/', $filename)) {
            abort(404, 'Invalid backup filename');
        }

        $filePath = storage_path('app/backups/' . $filename);

        if (!File::exists($filePath)) {
            abort(404, 'Backup file not found');
        }

        Log::info('Backup downloaded', [
            'filename' => $filename,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name
        ]);

        return response()->download($filePath);
    }

    /**
     * Delete a backup file
     */
    public function destroy($filename)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin only');
        }

        // Validate filename format
        if (!preg_match('/^backup_\d{4}-\d{2}-\d{2}_\d{6}\.sql\.gz$/', $filename)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Invalid backup filename');
        }

        $filePath = storage_path('app/backups/' . $filename);

        if (!File::exists($filePath)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Backup file not found');
        }

        File::delete($filePath);

        Log::warning('Backup deleted', [
            'filename' => $filename,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name
        ]);

        return redirect()->route('admin.backups.index')
            ->with('success', 'Backup deleted successfully');
    }

    /**
     * Upload and restore a backup file
     */
    public function upload(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin only');
        }

        $request->validate([
            'backup_file' => 'required|file|mimes:gz,sql|max:512000', // 500MB max
            'password' => 'required|string',
        ], [
            'backup_file.required' => 'Please select a backup file to upload',
            'backup_file.mimes' => 'Backup file must be .sql.gz or .sql format',
            'backup_file.max' => 'Backup file is too large (max 500MB)',
            'password.required' => 'Password is required for security confirmation',
        ]);

        // Verify admin password
        if (!Hash::check($request->password, Auth::user()->password)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Incorrect password. Restore cancelled.');
        }

        try {
            $file = $request->file('backup_file');
            $filename = 'restore_' . time() . '_' . $file->getClientOriginalName();
            $path = storage_path('app/backups/' . $filename);

            // Save uploaded file
            $file->move(storage_path('app/backups'), $filename);

            Log::warning('Backup file uploaded for restore', [
                'filename' => $filename,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name
            ]);

            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup uploaded successfully. You can now restore it.')
                ->with('uploaded_file', $filename);

        } catch (\Exception $e) {
            Log::error('Backup upload failed', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()->route('admin.backups.index')
                ->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Restore database from backup file
     */
    public function restore(Request $request, $filename)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized - Admin only');
        }

        $request->validate([
            'password' => 'required|string',
        ], [
            'password.required' => 'Password is required to confirm restore operation',
        ]);

        // Verify admin password
        if (!Hash::check($request->password, Auth::user()->password)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Incorrect password. Restore cancelled.');
        }

        $filePath = storage_path('app/backups/' . $filename);

        if (!File::exists($filePath)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Backup file not found');
        }

        try {
            // Create emergency backup before restore
            $this->info('Creating emergency backup before restore...');
            Artisan::call('backup:database');

            // Decompress if needed
            $sqlContent = '';
            if (str_ends_with($filename, '.gz')) {
                $sqlContent = gzdecode(file_get_contents($filePath));
            } else {
                $sqlContent = file_get_contents($filePath);
            }

            if ($sqlContent === false || empty($sqlContent)) {
                throw new \Exception('Failed to read backup file or file is empty');
            }

            // Get database configuration
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');

            // Save SQL to temporary file
            $tempSqlFile = storage_path('app/backups/temp_restore.sql');
            file_put_contents($tempSqlFile, $sqlContent);

            // Path to mysql client (XAMPP)
            $mysqlPath = $this->getMysqlPath();

            // Execute restore using mysql client
            $command = sprintf(
                '"%s" --host=%s --port=%s --user=%s --password=%s %s < "%s" 2>&1',
                $mysqlPath,
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbPassword),
                escapeshellarg($dbName),
                $tempSqlFile
            );

            exec($command, $output, $returnCode);

            // Clean up temp file
            if (File::exists($tempSqlFile)) {
                File::delete($tempSqlFile);
            }

            if ($returnCode !== 0) {
                throw new \Exception('Database restore failed: ' . implode("\n", $output));
            }

            Log::warning('Database restored from backup', [
                'filename' => $filename,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name
            ]);

            // Log user out (they need to re-login)
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Database restored successfully! Please log in again.');

        } catch (\Exception $e) {
            Log::error('Database restore failed', [
                'message' => $e->getMessage(),
                'filename' => $filename,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('admin.backups.index')
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Get path to mysql executable
     */
    private function getMysqlPath(): string
    {
        $xamppPath = 'C:/xampp/mysql/bin/mysql.exe';
        if (file_exists($xamppPath)) {
            return $xamppPath;
        }
        return 'mysql';
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Log info message (for consistency with command pattern)
     */
    private function info($message)
    {
        Log::info($message);
    }
}
