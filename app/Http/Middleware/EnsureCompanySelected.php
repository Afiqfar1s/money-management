<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanySelected
{
    /**
     * Ensure an authenticated user has a valid current company selected.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Not logged in? Let auth middleware handle it.
        if (!$user) {
            return $next($request);
        }

        // Admin can proceed without selecting a company (useful for company/user admin screens).
        if ($user->isAdmin()) {
            return $next($request);
        }

        $companyId = (int) $request->session()->get('current_company_id');

        if ($companyId <= 0) {
            // If user has access to exactly one company, auto-select it.
            $firstCompanyId = $user->companies()->orderBy('companies.name')->value('companies.id');
            if ($firstCompanyId) {
                $request->session()->put('current_company_id', (int) $firstCompanyId);
                return $next($request);
            }

            return redirect()->route('companies.select')
                ->with('error', 'Please select a company to continue.');
        }

        // Validate membership
        $hasAccess = $user->companies()->where('companies.id', $companyId)->exists();

        if (!$hasAccess) {
            $request->session()->forget('current_company_id');
            return redirect()->route('companies.select')
                ->with('error', 'You no longer have access to that company. Please select another.');
        }

        return $next($request);
    }
}
