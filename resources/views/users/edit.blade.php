@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update user information - {{ $user->name }}</p>
            </div>
            <a href="{{ route('users.index') }}" class="bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Back to Users
            </a>
        </div>
    </div>

    <!-- User Info Summary -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-300">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
            </div>
            <div class="flex gap-2">
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                    {{ ucfirst($user->role) }}
                </span>
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    {{ $user->companies->count() }} {{ Str::plural('Company', $user->companies->count()) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Section 1: Basic Information -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700" x-data="{ open: true }">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750" @click="open = !open">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h3>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
        
        <div x-show="open" x-collapse>
            <form action="{{ route('users.basic-info.update', $user) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Update Basic Info
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Section 2: Change Password -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700" x-data="{ open: true }">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750" @click="open = !open">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Change Password</h3>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
        
        <div x-show="open" x-collapse>
            <form action="{{ route('users.password.update', $user) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimum 8 characters</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Section 3: Role & Permissions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700" x-data="{ open: true, role: '{{ old('role', $user->role) }}' }">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750" @click="open = !open">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Role & Permissions</h3>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
        
        <div x-show="open" x-collapse>
            <form action="{{ route('users.role-permissions.update', $user) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            User Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" id="role" required x-model="role"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('role') border-red-500 @enderror">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Permissions (only for regular users) -->
                    <div x-show="role === 'user'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Custom Permissions</label>
                        
                        <div class="flex justify-end gap-2 mb-3">
                            <button type="button" onclick="document.querySelectorAll('input[name=\'permissions[]\']').forEach(el => el.checked = true)" 
                                class="text-xs px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                Select All
                            </button>
                            <button type="button" onclick="document.querySelectorAll('input[name=\'permissions[]\']').forEach(el => el.checked = false)" 
                                class="text-xs px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                Clear All
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                            @foreach(\App\Models\User::getAllPermissions() as $key => $label)
                            <label class="flex items-center p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                    {{ in_array($key, old('permissions', $user->permissions ?? [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500">
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div x-show="role === 'admin'" x-cloak class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            Admins have full access to all features automatically.
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Update Role & Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Section 4: Company Assignments -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700" x-data="{ open: true }">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750" @click="open = !open">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Company Assignments</h3>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
        
        <div x-show="open" x-collapse>
            <form action="{{ route('users.companies.update', $user) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Select Companies</label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                        @foreach(($companies ?? \App\Models\Company::orderBy('name')->get()) as $company)
                            <label class="flex items-center p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer">
                                <input type="checkbox" name="company_ids[]" value="{{ $company->id }}"
                                    {{ in_array($company->id, old('company_ids', $user->companies->pluck('id')->toArray())) ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500">
                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $company->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    @error('company_ids')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Update Companies
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
