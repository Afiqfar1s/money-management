<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::query()->orderBy('name')->paginate(20);
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:companies,code'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company-logos', 'public');
        }

        $validated['logo_path'] = $logoPath;

        Company::create($validated);

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        $users = User::query()->orderBy('name')->get();
        $assignedUserIds = $company->users()->pluck('users.id')->all();

        return view('companies.edit', compact('company', 'users', 'assignedUserIds'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:companies,code,' . $company->id],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        if (!empty($validated['remove_logo']) && $company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
            $company->logo_path = null;
        }

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $company->logo_path = $request->file('logo')->store('company-logos', 'public');
        }

        $company->update([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'logo_path' => $company->logo_path,
        ]);

        $company->users()->sync($validated['user_ids'] ?? []);

        return redirect()->route('companies.edit', $company)->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        if ($company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
        }
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
