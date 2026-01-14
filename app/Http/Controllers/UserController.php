<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withCount('debtors')->latest()->paginate(15);
        
        // Debug: Log what we're sending to the view
        \Log::info('UserController index:', [
            'user_count' => $users->count(),
            'total' => $users->total(),
            'users' => $users->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role
            ])
        ]);
        
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    $companies = Company::query()->orderBy('name')->get();

    return view('users.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['user', 'admin'])],
            'permissions' => ['nullable', 'array'],
            'company_ids' => ['nullable', 'array'],
            'company_ids.*' => ['integer', 'exists:companies,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Set default permissions for regular users if none provided
        if ($validated['role'] === 'user' && empty($validated['permissions'])) {
            $validated['permissions'] = User::getDefaultPermissions();
        }

    $user = User::create($validated);

    // Assign companies (many-to-many)
    $user->companies()->sync($validated['company_ids'] ?? []);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('debtors');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
    $companies = Company::query()->orderBy('name')->get();
    $assignedCompanyIds = $user->companies()->pluck('companies.id')->all();

    return view('users.edit', compact('user', 'companies', 'assignedCompanyIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['user', 'admin'])],
            'permissions' => ['nullable', 'array'],
            'company_ids' => ['nullable', 'array'],
            'company_ids.*' => ['integer', 'exists:companies,id'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Handle permissions based on role
        if ($validated['role'] === 'admin') {
            $validated['permissions'] = null; // Admins don't need specific permissions
        } elseif (!isset($validated['permissions'])) {
            $validated['permissions'] = User::getDefaultPermissions();
        }

        $user->update($validated);

        // Keep admin UX simple: allow assigning companies even for admins.
        $user->companies()->sync($validated['company_ids'] ?? []);

        // If the edited user is the current user and they no longer belong to the selected company,
        // clear the company context so middleware can re-select a valid one.
        if ($user->id === auth()->id()) {
            $currentCompanyId = session('current_company_id');
            if ($currentCompanyId && !$user->isAdmin() && !$user->companies()->where('companies.id', $currentCompanyId)->exists()) {
                session()->forget('current_company_id');
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting your own account
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account!');
        }

        // Prevent deleting the last admin
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete the last admin account!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }
}
