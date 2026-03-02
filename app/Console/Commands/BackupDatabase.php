<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--keep-days=30 : Number of days to keep backups}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a compressed backup of the MySQL database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup...');

        try {
            // Get database configuration
            // Use backup-specific credentials if available, otherwise fall back to regular DB user
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUser = env('DB_BACKUP_USERNAME', env('DB_USERNAME', 'root'));
            $dbPassword = env('DB_BACKUP_PASSWORD', env('DB_PASSWORD', 'admin'));

            // Create backup directory if it doesn't exist
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
                $this->info('Created backup directory: ' . $backupDir);
            }

            // Generate backup filename with timestamp
            $timestamp = now()->format('Y-m-d_His');
            $filename = "backup_{$timestamp}.sql";
            $compressedFilename = "{$filename}.gz";
            $sqlPath = $backupDir . '/' . $filename;
            $gzPath = $backupDir . '/' . $compressedFilename;

            // Path to mysqldump (XAMPP)
            $mysqldumpPath = $this->getMysqldumpPath();
            
            if (!file_exists($mysqldumpPath)) {
                $this->error("mysqldump not found at: {$mysqldumpPath}");
                $this->error("Please check your MySQL installation path.");
                return 1;
            }

            $this->info('Creating SQL dump...');

            // Build mysqldump command (Windows compatible)
            $command = sprintf(
                '"%s" --host=%s --port=%s --user=%s --password=%s --single-transaction --routines --triggers --events %s > "%s" 2>&1',
                $mysqldumpPath,
                $dbHost,
                $dbPort,
                $dbUser,
                $dbPassword,
                $dbName,
                $sqlPath
            );

            // Execute mysqldump
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $this->error('Backup failed! mysqldump returned error code: ' . $returnCode);
                $this->error('Output: ' . implode("\n", $output));
                
                // Clean up failed backup
                if (File::exists($sqlPath)) {
                    File::delete($sqlPath);
                }
                
                Log::error('Database backup failed', [
                    'return_code' => $returnCode,
                    'output' => $output
                ]);
                
                return 1;
            }

            // Check if SQL file was created and has content
            if (!File::exists($sqlPath) || File::size($sqlPath) === 0) {
                $this->error('Backup failed! SQL file is empty or was not created.');
                return 1;
            }

            $sqlSize = File::size($sqlPath);
            $this->info("SQL dump created: " . number_format($sqlSize / 1024, 2) . " KB");

            // Compress the SQL file
            $this->info('Compressing backup...');
            
            $sqlContent = file_get_contents($sqlPath);
            $gzContent = gzencode($sqlContent, 9); // Maximum compression
            file_put_contents($gzPath, $gzContent);

            // Delete uncompressed SQL file
            File::delete($sqlPath);

            $gzSize = File::size($gzPath);
            $compressionRatio = round((1 - $gzSize / $sqlSize) * 100, 1);
            
            $this->info("Backup compressed: " . number_format($gzSize / 1024, 2) . " KB ({$compressionRatio}% smaller)");
            $this->info("Backup saved: {$compressedFilename}");

            // Clean up old backups
            $keepDays = $this->option('keep-days');
            $this->cleanupOldBackups($backupDir, $keepDays);

            Log::info('Database backup completed successfully', [
                'filename' => $compressedFilename,
                'size' => $gzSize
            ]);

            $this->info('✅ Backup completed successfully!');
            
            return 0;

        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            Log::error('Database backup exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Get the path to mysqldump executable
     */
    private function getMysqldumpPath(): string
    {
        // Try XAMPP path first (Windows)
        $xamppPath = 'C:/xampp/mysql/bin/mysqldump.exe';
        if (file_exists($xamppPath)) {
            return $xamppPath;
        }

        // Try system path
        return 'mysqldump';
    }

    /**
     * Clean up old backup files
     */
    private function cleanupOldBackups(string $backupDir, int $keepDays): void
    {
        $this->info("Cleaning up backups older than {$keepDays} days...");

        $cutoffDate = now()->subDays($keepDays);
        $deletedCount = 0;
        $freedSpace = 0;

        $files = File::glob($backupDir . '/backup_*.sql.gz');

        foreach ($files as $file) {
            $fileTime = File::lastModified($file);
            
            if ($fileTime < $cutoffDate->timestamp) {
                $size = File::size($file);
                File::delete($file);
                $deletedCount++;
                $freedSpace += $size;
                
                $this->line('Deleted old backup: ' . basename($file));
            }
        }

        if ($deletedCount > 0) {
            $this->info("Deleted {$deletedCount} old backup(s), freed " . number_format($freedSpace / 1024 / 1024, 2) . " MB");
        } else {
            $this->info('No old backups to delete.');
        }
    }
}
