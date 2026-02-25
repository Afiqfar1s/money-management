@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Page Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create New Company</h1>
                    <p class="text-sm text-gray-600 mt-1">Add a new company to the system</p>
                </div>
                <a href="{{ route('companies.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Back to Companies
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Company Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Code <span class="text-gray-500">(optional)</span>
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('code') border-red-500 @enderror"
                            placeholder="e.g., MNHR, CORP001">
                        @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">A unique identifier for this company</p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number <span class="text-gray-500">(optional)</span>
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror"
                            placeholder="e.g., +60123456789">
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Address <span class="text-gray-500">(optional)</span>
                        </label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror"
                            placeholder="Company address">{{ old('address') }}</textarea>
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Logo -->
                    <div x-data="{ 
                        imageUrl: null,
                        handleFileChange(event) {
                            const file = event.target.files[0];
                            if (file) {
                                this.imageUrl = URL.createObjectURL(file);
                            } else {
                                this.imageUrl = null;
                            }
                        }
                    }">
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Logo <span class="text-gray-500">(optional)</span>
                        </label>
                        
                        <!-- Image Preview -->
                        <div x-show="imageUrl" class="mb-3" style="display: none;">
                            <div class="relative inline-block">
                                <img :src="imageUrl" alt="Logo preview" class="h-32 w-32 object-contain border-2 border-gray-300 rounded-lg bg-white p-2">
                                <button type="button" @click="imageUrl = null; document.getElementById('logo').value = ''" 
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <input type="file" name="logo" id="logo" accept="image/*" @change="handleFileChange($event)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('logo') border-red-500 @enderror">
                        @error('logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 2MB. Recommended size: 400x400px</p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('companies.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Create Company
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
