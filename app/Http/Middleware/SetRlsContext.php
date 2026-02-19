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
        if (Auth::check()) {
            $user = Auth::user();
            
            // Get current company from session (you may already have this logic)
            $currentCompanyId = session('current_company_id');
            
            // Set PostgreSQL session variables for RLS
            DB::statement("SET app.current_user_id = ?", [$user->id]);
            DB::statement("SET app.is_admin = ?", [$user->isAdmin() ? 'true' : 'false']);
            
            if ($currentCompanyId) {
                DB::statement("SET app.current_company_id = ?", [$currentCompanyId]);
            }
        } else {
            // No authenticated user - set null context
            DB::statement("SET app.current_user_id = ''");
            DB::statement("SET app.is_admin = 'false'");
            DB::statement("SET app.current_company_id = ''");
        }

        return $next($request);
    }
}
