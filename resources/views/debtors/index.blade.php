@extends('layouts.app')

@section('content')
<div x-data="{
    searchQuery: '',
    statusFilter: 'all',
    loading: false,
    async search() {
        this.loading = true;
        const params = new URLSearchParams();
        if (this.searchQuery) params.append('q', this.searchQuery);
        if (this.statusFilter !== 'all') params.append('status', this.statusFilter);
        
        try {
            const response = await fetch(`{{ route('debtors.index') }}?${params}`);
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.getElementById('debtors-tbody');
            if (newTable) {
                document.getElementById('debtors-tbody').innerHTML = newTable.innerHTML;
            }
        } catch (error) {
            console.error('Search failed:', error);
        } finally {
            this.loading = false;
        }
    }
}" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Debtor Management</h1>
                <p class="text-sm text-gray-600 mt-1">Track and manage your debtor records</p>
            </div>
            @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('create_debtors'))
            <a href="{{ route('debtors.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                Add New Debtor
            </a>
            @endif
        </div>
    </div>

    <!-- Summary Box - Horizontal Side by Side -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 md:gap-8">
            <!-- Total Outstanding -->
            <div class="flex items-center gap-4 flex-1">
                <div class="flex-shrink-0 w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Outstanding</p>
                    <p class="text-2xl font-bold text-gray-900">RM {{ number_format((float)$total_outstanding, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Amount to collect</p>
                </div>
            </div>

            <!-- Divider -->
            <div class="hidden md:block w-px h-16 bg-gray-200"></div>

            <!-- Total Collected -->
            <div class="flex items-center gap-4 flex-1">
                <div class="flex-shrink-0 w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Collected</p>
                    <p class="text-2xl font-bold text-gray-900">RM {{ number_format((float)$total_paid, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $total_outstanding > 0 ? number_format(($total_paid / ($total_paid + $total_outstanding)) * 100, 1) : 100 }}% collection rate
                    </p>
                </div>
            </div>

            <!-- Divider -->
            <div class="hidden md:block w-px h-16 bg-gray-200"></div>

            <!-- Total Debtors -->
            <div class="flex items-center gap-4 flex-1">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Debtors</p>
                    @php
                        $settledCount = $debtors->where('outstanding', '<=', 0)->count();
                        $owingCount = $total_debtors - $settledCount;
                    @endphp
                    <p class="text-2xl font-bold text-gray-900">{{ $total_debtors }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $owingCount }} owing, {{ $settledCount }} settled</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center">
            <!-- Search Input - Takes most space -->
            <div class="flex-1">
                <input 
                    type="text" 
                    x-model="searchQuery" 
                    @input.debounce.300ms="search()"
                    placeholder="Search debtor by name or description..." 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base">
            </div>
            
            <!-- Status Filter Dropdown -->
            <div class="w-full md:w-56">
                <select 
                    x-model="statusFilter" 
                    @change="search()"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base">
                    <option value="all">All Status</option>
                    <option value="owing">Owing Only</option>
                    <option value="settled">Settled Only</option>
                </select>
            </div>

            <!-- Clear Button -->
            <button 
                @click="searchQuery = ''; statusFilter = 'all'; search()" 
                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-colors whitespace-nowrap">
                Clear
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" x-cloak class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-200 border-t-indigo-600 mx-auto"></div>
        <p class="text-gray-600 mt-4">Loading...</p>
    </div>

    <!-- Debtors Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" x-show="!loading" x-cloak>
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Debtor Records</h3>
                <span class="text-sm text-gray-600">
                    Showing {{ $debtors->count() }} of {{ $debtors->total() }} records
                </span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Debtor Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Type</th>
                        @if(auth()->user()->isAdmin())
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Owner</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Outstanding</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Last Payment</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="debtors-tbody">
                    @forelse ($debtors as $debtor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $debtor->name }}</div>
                                @if($debtor->ic_number)
                                <div class="text-xs text-gray-500">IC: {{ $debtor->ic_number }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($debtor->debtor_type === 'individual')
                                    <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800">
                                        Individual
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded bg-purple-100 text-purple-800">
                                        Company
                                    </span>
                                @endif
                            </td>
                            @if(auth()->user()->isAdmin())
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $debtor->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $debtor->user->email }}</div>
                            </td>
                            @endif
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 truncate max-w-xs">{{ $debtor->description ?: '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($debtor->outstanding > 0)
                                    <span class="text-sm font-semibold text-red-600">RM {{ number_format((float)$debtor->outstanding, 2) }}</span>
                                @else
                                    <span class="text-sm font-semibold text-green-600">Settled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if ($debtor->payments_max_paid_at)
                                    {{ \Carbon\Carbon::parse($debtor->payments_max_paid_at)->format('d M Y') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('debtors.show', $debtor) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 font-medium"
                                       title="View details">
                                        View
                                    </a>

                                    @php
                                        $canEdit = auth()->user()->isAdmin() 
                                            || auth()->user()->hasPermission('edit_all_debtors')
                                            || (auth()->user()->hasPermission('edit_own_debtors') && $debtor->user_id === auth()->id());
                                        $canDelete = auth()->user()->isAdmin()
                                            || auth()->user()->hasPermission('delete_all_debtors')
                                            || (auth()->user()->hasPermission('delete_own_debtors') && $debtor->user_id === auth()->id());
                                    @endphp

                                    @if($canEdit)
                                    <a href="{{ route('debtors.edit', $debtor) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 font-medium"
                                       title="Edit debtor">
                                        Edit
                                    </a>
                                    @endif

                                    @if($canDelete)
                                    <form method="POST" action="{{ route('debtors.destroy', $debtor) }}" class="inline" onsubmit="return confirm('Delete this debtor? This will permanently remove all payment history and balance adjustments. This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 font-medium"
                                                title="Delete debtor">
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No debtors found</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    @if(request('q') || request('status') !== 'all')
                                        No results match your search criteria.
                                    @else
                                        Get started by adding a new debtor.
                                    @endif
                                </p>
                                @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('create_debtors'))
                                <div class="mt-6">
                                    <a href="{{ route('debtors.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Add debtor
                                    </a>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($debtors->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $debtors->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
