<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetRlsContext
{
    /**
     * Set PostgreSQL session variables for Row Level Security.
     *
     * This middleware sets the current user context so that Supabase RLS
     * policies can enforce access control at the database level.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply RLS context for PostgreSQL connections
        if (config('database.default') !== 'pgsql') {
            return $next($request);
        }

        try {
            if (Auth::check()) {
                $user = Auth::user();
                
                // Get current company from session (you may already have this logic)
                $currentCompanyId = session('current_company_id');
                
                // Sanitize values - only allow integers for IDs
                $userId = (int) $user->id;
                $isAdmin = $user->isAdmin() ? 'true' : 'false';
                $companyId = $currentCompanyId ? (int) $currentCompanyId : '';
                
                // Set PostgreSQL session variables for RLS
                // Note: SET doesn't support prepared statements, so we use sanitized values
                DB::statement("SET LOCAL app.current_user_id = '{$userId}'");
                DB::statement("SET LOCAL app.is_admin = '{$isAdmin}'");
                DB::statement("SET LOCAL app.current_company_id = '{$companyId}'");
            } else {
                // No authenticated user - set empty context
                DB::statement("SET LOCAL app.current_user_id = ''");
                DB::statement("SET LOCAL app.is_admin = 'false'");
                DB::statement("SET LOCAL app.current_company_id = ''");
            }
        } catch (\Exception $e) {
            // Log the error but don't break the application
            // RLS will default to restrictive behavior
            \Log::warning('Failed to set RLS context: ' . $e->getMessage());
        }

        return $next($request);
    }
}
