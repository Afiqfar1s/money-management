@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Companies</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Manage tenant companies and user access</p>
                </div>
                <a href="{{ route('companies.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Company
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

        <!-- Companies Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Logo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Users</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($companies as $company)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    @if($company->logo_url)
                                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }} logo" class="h-12 w-12 rounded-lg object-contain border-2 border-gray-300 dark:border-gray-600 shadow-sm bg-white dark:bg-gray-800 p-1" />
                                    @else
                                        <div class="h-12 w-12 rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $company->name }}</div>
                                    @if($company->address)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($company->address, 40) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($company->code)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                            {{ $company->code }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                        {{ $company->users()->count() }} {{ Str::plural('user', $company->users()->count()) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium text-sm transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('companies.destroy', $company) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete {{ $company->name }}? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium text-sm transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No companies</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new company.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('companies.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            New Company
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($companies->hasPages())
            <div class="mt-6">
                {{ $companies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
