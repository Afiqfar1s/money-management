@extends('layouts.app')

@section('content')
    <!-- Page Header with Gradient -->
    <div class="bg-indigo-600 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-white mb-2">Create New User</h1>
            <p class="text-indigo-100">Add a new user to the system</p>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <form action="{{ route('users.store') }}" method="POST" class="p-8">
                    @csrf

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror">
                        @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('email') border-red-500 @enderror">
                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('password') border-red-500 @enderror">
                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                    </div>

                    <!-- Role -->
                    <div class="mb-8" x-data="{ role: '{{ old('role', 'user') }}' }">
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">User Role</label>
                        <select name="role" id="role" required x-model="role"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('role') border-red-500 @enderror">
                            <option value="user">User - Regular access with custom permissions</option>
                            <option value="admin">Admin - Full system access</option>
                        </select>
                        @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Permissions Section (shown only for regular users) -->
                        <div x-show="role === 'user'" x-cloak class="mt-6 p-6 bg-gray-50 rounded-lg border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                User Permissions
                            </h3>
                            <p class="text-xs text-gray-500 mb-4">Select the permissions this user will have. Uncheck to restrict access.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach(\App\Models\User::getAllPermissions() as $key => $label)
                                <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-colors">
                                    <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                        {{ in_array($key, old('permissions', \App\Models\User::getDefaultPermissions())) ? 'checked' : '' }}
                                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Company Assignment -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-gray-700">Companies (can manage)</label>
                            <a href="{{ route('companies.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Manage companies</a>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">Select one or more companies for this user. Leave blank to assign later.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(($companies ?? collect()) as $company)
                                <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-colors">
                                    <input type="checkbox" name="company_ids[]" value="{{ $company->id }}"
                                        {{ in_array($company->id, old('company_ids', [])) ? 'checked' : '' }}
                                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">{{ $company->name }}</span>
                                </label>
                            @endforeach
                        </div>

                        @error('company_ids')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('company_ids.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('users.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-colors shadow-sm                             Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
