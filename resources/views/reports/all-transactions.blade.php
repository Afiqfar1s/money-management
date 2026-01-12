@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">All Transactions Report</h1>
                    <p class="text-sm text-gray-600 mt-1">View and download complete transaction history for the selected company</p>
                </div>
                <span class="px-4 py-2 bg-purple-100 text-purple-800 rounded-lg text-sm font-semibold">
                    Administrator Only
                </span>
            </div>
        </div>
    </div>

    <div class="py-6 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Filters Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Filters</h2>
                <form method="GET" action="{{ route('reports.all-transactions') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="debtor" class="block text-sm font-medium text-gray-700 mb-2">Debtor</label>
                            <input type="text" name="debtor" id="debtor" value="{{ request('debtor') }}" 
                                placeholder="Search by debtor/company..." 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="from_date" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                            <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="to_date" class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                            <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">
                            Apply Filters
                        </button>
                        <a href="{{ route('reports.all-transactions') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">
                            Clear Filters
                        </a>
                        <a href="{{ route('reports.all-transactions.download', request()->all()) }}" 
                            class="ml-auto px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Debtor / Company</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Voucher No.</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Amount (RM)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-semibold text-gray-900">{{ $transaction->debtor_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($transaction->type === 'Payment')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Payment
                                        </span>
                                    @elseif($transaction->type === 'Balance Addition')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Addition
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Deduction
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $transaction->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->voucher_no ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900">
                                    {{ number_format($transaction->amount, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="mt-4 text-lg font-medium text-gray-900">No transactions found</h3>
                                    <p class="mt-2 text-sm text-gray-600">Try adjusting your filters to see more results.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
