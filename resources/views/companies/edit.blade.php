@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Company</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Manage company info and assign users</p>
                </div>
                <a href="{{ route('companies.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Edit Form -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <form method="POST" action="{{ route('companies.update', $company) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Company Information Section -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Company Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Company Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Company Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Company Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Company Code
                                </label>
                                <input type="text" id="code" name="code" value="{{ old('code', $company->code) }}"
                                    placeholder="e.g., ABC123"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors">
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Phone Number
                                </label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $company->phone) }}"
                                    placeholder="e.g., +60123456789"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Address
                                </label>
                                <textarea id="address" name="address" rows="3"
                                    placeholder="Enter company address"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors">{{ old('address', $company->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Company Logo -->
                            <div class="md:col-span-2">
                                <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Company Logo
                                </label>
                                <div class="flex items-start gap-4">
                                    @if($company->logo_url)
                                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }} logo" 
                                            class="h-20 w-20 rounded-lg object-contain border-2 border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-800 p-2" />
                                    @else
                                        <div class="h-20 w-20 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <input type="file" id="logo" name="logo" accept="image/*"
                                            class="block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300 dark:hover:file:bg-indigo-900/70 transition-colors">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Upload a new logo to replace the current one. PNG/JPG up to 2MB.</p>
                                        @error('logo')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror

                                        @if($company->logo_url)
                                            <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                <input type="checkbox" name="remove_logo" value="1" 
                                                    class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700">
                                                Remove current logo
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Assignment Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Assign Users</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Select which users can access this company</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($users as $user)
                                <label class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors {{ in_array($user->id, old('user_ids', $assignedUserIds)) ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-200 dark:border-indigo-800' : '' }}">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" 
                                        class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700"
                                        {{ in_array($user->id, old('user_ids', $assignedUserIds)) ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $user->email }}
                                            @if($user->isAdmin())
                                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                                    Admin
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Changes
                        </button>
                        <a href="{{ route('companies.index') }}" class="inline-flex items-center px-6 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg text-sm font-medium transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
