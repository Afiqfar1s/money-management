<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanyContextController extends Controller
{
    public function select(Request $request)
    {
        $user = $request->user();

        // Admin doesn't need a company context to use the app's admin screens.
        if ($user->isAdmin()) {
            $companies = Company::orderBy('name')->get();
        } else {
            $companies = $user->companies()->orderBy('companies.name')->get();
        }

        $currentCompanyId = $request->session()->get('current_company_id');

        return view('companies.select', compact('companies', 'currentCompanyId'));
    }

    public function switch(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'company_id' => ['required', 'integer'],
        ]);

        $companyId = (int) $validated['company_id'];

        // Admin can switch to any existing company.
        if ($user->isAdmin()) {
            Company::query()->findOrFail($companyId);
            $request->session()->put('current_company_id', $companyId);
            return back()->with('success', 'Company switched.');
        }

        $company = $user->companies()->where('companies.id', $companyId)->first();
        if (!$company) {
            abort(403, 'Unauthorized company access');
        }

        $request->session()->put('current_company_id', $companyId);

        return back()->with('success', 'Company switched.');
    }
}
