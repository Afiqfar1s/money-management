@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Page Header -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">System overview and performance metrics</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-lg text-sm font-semibold">
                        Administrator
                    </span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ now()->format('l, F j, Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        
        <!-- System Health Alerts -->
        @if(count($alerts) > 0)
        <div class="space-y-3">
            @foreach($alerts as $alert)
            <div class="bg-{{ $alert['type'] === 'warning' ? 'yellow' : 'blue' }}-50 dark:bg-{{ $alert['type'] === 'warning' ? 'yellow' : 'blue' }}-900/20 border-l-4 border-{{ $alert['type'] === 'warning' ? 'yellow' : 'blue' }}-500 rounded-r-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-{{ $alert['type'] === 'warning' ? 'yellow' : 'blue' }}-600 dark:text-{{ $alert['type'] === 'warning' ? 'yellow' : 'blue' }}-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-{{ $alert['type'] === 'warning' ? 'yellow' : 'blue' }}-800 dark:text-{{ $alert['type'] === 'warning' ? 'yellow' : 'blue' }}-200">
                            {{ $alert['message'] }}
                        </span>
                    </div>
                    @if($alert['action_url'])
                    <a href="{{ $alert['action_url'] }}" class="text-sm font-medium text-{{ $alert['type'] === 'warning' ? 'yellow' : 'blue' }}-700 dark:text-{{ $alert['type'] === 'warning' ? 'yellow' : 'blue' }}-300 hover:underline">
                        {{ $alert['action_text'] }} →
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Phase 1: Overview Statistics Cards -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">System Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Users -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Users</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalUsers }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $totalAdmins }} Admins, {{ $totalRegularUsers }} Users</p>
                        </div>
                        <div class="h-12 w-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Companies -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Companies</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalCompanies }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Active organizations</p>
                        </div>
                        <div class="h-12 w-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Debtors -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Debtors</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalDebtors }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">System-wide</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Sessions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Active Sessions</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $activeSessions }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Last 15 minutes</p>
                        </div>
                        <div class="h-12 w-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Overview -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total Outstanding -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Outstanding</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">RM {{ number_format($totalOutstanding, 2) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">All companies</p>
                        </div>
                        <div class="h-12 w-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Payments This Month -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Payments This Month</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">RM {{ number_format($paymentsThisMonth, 2) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ now()->format('F Y') }}</p>
                        </div>
                        <div class="h-12 w-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Payments Today -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Payments Today</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-2">RM {{ number_format($paymentsToday, 2) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ now()->format('M d, Y') }}</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <a href="{{ route('users.create') }}" class="flex flex-col items-center justify-center px-4 py-4 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300 text-center">New User</span>
                </a>

                <a href="{{ route('companies.create') }}" class="flex flex-col items-center justify-center px-4 py-4 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 rounded-lg transition-colors border border-purple-200 dark:border-purple-800">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-sm font-medium text-purple-700 dark:text-purple-300 text-center">New Company</span>
                </a>

                <a href="{{ route('companies.index') }}" class="flex flex-col items-center justify-center px-4 py-4 bg-orange-50 dark:bg-orange-900/20 hover:bg-orange-100 dark:hover:bg-orange-900/30 rounded-lg transition-colors border border-orange-200 dark:border-orange-800">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-sm font-medium text-orange-700 dark:text-orange-300 text-center">Companies</span>
                </a>

                <a href="{{ route('sessions.index') }}" class="flex flex-col items-center justify-center px-4 py-4 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-lg transition-colors border border-green-200 dark:border-green-800">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-medium text-green-700 dark:text-green-300 text-center">Sessions</span>
                </a>

                <a href="{{ route('reports.all-transactions') }}" class="flex flex-col items-center justify-center px-4 py-4 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition-colors border border-blue-200 dark:border-blue-800">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm font-medium text-blue-700 dark:text-blue-300 text-center">Reports</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Phase 2: Company Performance Overview -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Company Performance</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Overview of all companies</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($companyPerformance as $company)
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    @if($company['logo_path'])
                                    <img src="{{ asset('storage/' . $company['logo_path']) }}" alt="{{ $company['name'] }}" class="h-10 w-10 rounded object-cover">
                                    @else
                                    <div class="h-10 w-10 rounded bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-300">{{ substr($company['name'], 0, 1) }}</span>
                                    </div>
                                    @endif
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $company['name'] }}</h3>
                                        @if($company['code'])
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $company['code'] }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-red-600 dark:text-red-400">RM {{ number_format($company['total_outstanding'], 2) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Outstanding</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $company['debtors_count'] }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Debtors</p>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $company['users_count'] }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Users</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-green-600 dark:text-green-400">RM {{ number_format($company['payments_this_month'], 2) }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Paid (Month)</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>No companies found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Phase 3: Recent Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Latest system activities</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentActivities as $activity)
                        <div class="flex items-start gap-3">
                            <div class="mt-1 h-8 w-8 rounded-full bg-{{ $activity['type'] === 'login' ? 'blue' : ($activity['type'] === 'payment' ? 'green' : ($activity['type'] === 'debtor' ? 'purple' : 'orange')) }}-100 dark:bg-{{ $activity['type'] === 'login' ? 'blue' : ($activity['type'] === 'payment' ? 'green' : ($activity['type'] === 'debtor' ? 'purple' : 'orange')) }}-900 flex items-center justify-center flex-shrink-0">
                                @if($activity['icon'] === 'login')
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                @elseif($activity['icon'] === 'cash')
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                @elseif($activity['icon'] === 'user-plus')
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                @else
                                <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $activity['meta'] }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $activity['timestamp']->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>No recent activity</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Debtors (System-Wide) -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Top Debtors by Outstanding</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Highest outstanding amounts across all companies</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Debtor Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Company</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($topDebtors as $index => $debtor)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $debtor->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $debtor->ic_no ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $debtor->company->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-semibold text-red-600 dark:text-red-400">RM {{ number_format($debtor->outstanding, 2) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                No debtors found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
