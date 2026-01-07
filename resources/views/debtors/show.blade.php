@extends('layouts.app')

@section('content')
<!-- Breadcrumb -->
<nav class="mb-6 flex items-center space-x-2 text-sm text-gray-500">
    <a href="{{ route('debtors.index') }}" class="hover:text-indigo-600">Dashboard</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
    <span class="text-gray-900 font-medium">{{ $debtor->name }}</span>
</nav>

<div class="space-y-6">
    <!-- Debtor Header Card -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="bg-indigo-600 px-6 py-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl font-bold">{{ $debtor->name }}</h1>
                        @if ($debtor->debtor_type === 'individual')
                            <span class="px-3 py-1 bg-white/90 text-white text-xs font-semibold rounded-full flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                Individual / Staff
                            </span>
                        @else
                            <span class="px-3 py-1 bg-white/90 text-white text-xs font-semibold rounded-full flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                </svg>
                                Company
                            </span>
                        @endif
                    </div>
                    @if($debtor->description)
                        <p class="text-indigo-100 text-sm">{{ $debtor->description }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('debtors.edit', $debtor) }}" class="px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors font-medium inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('debtors.refresh', $debtor) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-white/90 text-white rounded-lg hover:bg-opacity-30 transition-colors font-medium inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="grid grid-cols-3 gap-px bg-gray-200">
            <div class="bg-white px-6 py-4">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Outstanding Balance</div>
                @if ($debtor->outstanding > 0)
                    <div class="text-2xl font-bold text-red-600">RM {{ number_format((float)$debtor->outstanding, 2) }}</div>
                @else
                    <div class="text-2xl font-bold text-green-600">Settled ✓</div>
                @endif
            </div>
            <div class="bg-white px-6 py-4">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Starting Outstanding</div>
                <div class="text-2xl font-bold text-gray-900">RM {{ number_format((float)$debtor->starting_outstanding, 2) }}</div>
            </div>
            <div class="bg-white px-6 py-4">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Paid</div>
                <div class="text-2xl font-bold text-green-600">RM {{ number_format((float)$total_paid, 2) }}</div>
            </div>
        </div>

        <!-- Individual/Staff Details -->
        @if ($debtor->debtor_type === 'individual' && ($debtor->staff_number || $debtor->ic_number || $debtor->phone_number))
            <div class="px-6 py-6 border-t border-gray-200 bg-gray-50">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    Staff Information
                </h3>
                <div class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    @if ($debtor->staff_number)
                        <div class="flex items-start">
                            <span class="text-gray-600 w-32 flex-shrink-0">Staff Number:</span>
                            <span class="text-gray-900 font-medium">{{ $debtor->staff_number }}</span>
                        </div>
                    @endif
                    @if ($debtor->ic_number)
                        <div class="flex items-start">
                            <span class="text-gray-600 w-32 flex-shrink-0">IC Number:</span>
                            <span class="text-gray-900 font-medium">{{ $debtor->ic_number }}</span>
                        </div>
                    @endif
                    @if ($debtor->phone_number)
                        <div class="flex items-start">
                            <span class="text-gray-600 w-32 flex-shrink-0">Phone:</span>
                            <span class="text-gray-900 font-medium">{{ $debtor->phone_number }}</span>
                        </div>
                    @endif
                    @if ($debtor->position)
                        <div class="flex items-start">
                            <span class="text-gray-600 w-32 flex-shrink-0">Position:</span>
                            <span class="text-gray-900 font-medium">{{ $debtor->position }}</span>
                        </div>
                    @endif
                    @if ($debtor->start_working_date)
                        <div class="flex items-start">
                            <span class="text-gray-600 w-32 flex-shrink-0">Start Date:</span>
                            <span class="text-gray-900 font-medium">{{ $debtor->start_working_date->format('d M Y') }}</span>
                        </div>
                    @endif
                    @if ($debtor->resign_date)
                        <div class="flex items-start">
                            <span class="text-gray-600 w-32 flex-shrink-0">Resign Date:</span>
                            <span class="text-red-600 font-medium">{{ $debtor->resign_date->format('d M Y') }}</span>
                        </div>
                    @endif
                </div>
                @if ($debtor->address)
                    <div class="mt-4 text-sm flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">Address:</span>
                        <p class="text-gray-900 flex-1">{{ $debtor->address }}</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Company Details -->
        @if ($debtor->debtor_type === 'company' && ($debtor->ssm_number || $debtor->office_phone))
            <div class="px-6 py-6 border-t border-gray-200 bg-gray-50">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                    </svg>
                    Company Information
                </h3>
                <div class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    @if ($debtor->ssm_number)
                        <div class="flex items-start">
                            <span class="text-gray-600 w-32 flex-shrink-0">SSM Number:</span>
                            <span class="text-gray-900 font-medium">{{ $debtor->ssm_number }}</span>
                        </div>
                    @endif
                    @if ($debtor->office_phone)
                        <div class="flex items-start">
                            <span class="text-gray-600 w-32 flex-shrink-0">Office Phone:</span>
                            <span class="text-gray-900 font-medium">{{ $debtor->office_phone }}</span>
                        </div>
                    @endif
                </div>
                @if ($debtor->company_address)
                    <div class="mt-4 text-sm flex">
                        <span class="text-gray-600 w-32 flex-shrink-0">Company Address:</span>
                        <p class="text-gray-900 flex-1">{{ $debtor->company_address }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Action Forms - Two columns -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Add Payment Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 rounded-lg p-2 mr-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Record Payment</h3>
                    <p class="text-sm text-gray-600">Enter payment details from receipt</p>
                </div>
            </div>
            <form method="POST" action="{{ route('payments.store', $debtor) }}" class="space-y-4">
                @csrf
                <div>
                    <label for="payment_voucher_no" class="block text-sm font-medium text-gray-700 mb-1">Voucher Number *</label>
                    <input type="text" name="voucher_no" id="payment_voucher_no" required placeholder="e.g., VCH-001, RCP-123" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>
                <div>
                    <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (RM) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">RM</span>
                        <input type="number" name="amount" id="payment_amount" step="0.01" min="0.01" required class="w-full pl-12 rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>
                <div>
                    <label for="payment_paid_at" class="block text-sm font-medium text-gray-700 mb-1">Payment Date/Time</label>
                    <input type="datetime-local" name="paid_at" id="payment_paid_at" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to use current time</p>
                </div>
                <div>
                    <label for="payment_note" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                    <input type="text" name="note" id="payment_note" placeholder="e.g., Bank transfer, Cash, Cheque" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>
                <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Record Payment
                </button>
            </form>
        </div>

        <!-- Add Existing Balance Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 rounded-lg p-2 mr-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Add Balance</h3>
                    <p class="text-sm text-gray-600">Add additional debt amount</p>
                </div>
            </div>
            <form method="POST" action="{{ route('adjustments.store', $debtor) }}" class="space-y-4">
                @csrf
                <div>
                    <label for="adjustment_voucher_no" class="block text-sm font-medium text-gray-700 mb-1">Voucher Number</label>
                    <input type="text" name="voucher_no" id="adjustment_voucher_no" placeholder="e.g., INV-001 (Optional)" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>
                <div>
                    <label for="adjustment_amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (RM) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">RM</span>
                        <input type="number" name="amount" id="adjustment_amount" step="0.01" min="0.01" required class="w-full pl-12 rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    </div>
                </div>
                <div>
                    <label for="adjustment_adjusted_at" class="block text-sm font-medium text-gray-700 mb-1">Adjustment Date/Time</label>
                    <input type="datetime-local" name="adjusted_at" id="adjustment_adjusted_at" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to use current time</p>
                </div>
                <div>
                    <label for="adjustment_note" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                    <input type="text" name="note" id="adjustment_note" placeholder="e.g., Additional charges, Late fees" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>
                <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Balance
                </button>
            </form>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="grid grid-cols-1 gap-6">
        <!-- Payment History -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4 border-b border-green-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900">Payment History</h3>
                    </div>
                    <span class="px-3 py-1 bg-green-600 text-white rounded-full text-sm font-semibold">{{ $payments->total() }} payments</span>
                </div>
                <p class="text-sm text-gray-600 mt-1">Click voucher number to view/print receipt</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Voucher No.</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Note</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($payments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-gray-900">{{ $payment->paid_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('payments.voucher', $payment) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors font-mono font-semibold text-sm" target="_blank" title="View/Print Receipt">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ $payment->voucher_no }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1.5 bg-green-100 text-green-800 rounded-lg font-bold text-sm border border-green-200">
                                        RM {{ number_format((float)$payment->amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $payment->note ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="mt-4 text-lg font-medium text-gray-500">No payments recorded yet</p>
                                    <p class="mt-2 text-sm text-gray-400">Start by recording a payment above</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($payments->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

        <!-- Adjustments History -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4 border-b border-red-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900">Balance Adjustments</h3>
                    </div>
                    <span class="px-3 py-1 bg-red-600 text-white rounded-full text-sm font-semibold">{{ $adjustments->total() }} adjustments</span>
                </div>
                <p class="text-sm text-gray-600 mt-1">Additional balances added to the account</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Voucher No.</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Note</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($adjustments as $adjustment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-gray-900">{{ $adjustment->adjusted_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($adjustment->voucher_no)
                                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg font-mono font-semibold text-sm">
                                            {{ $adjustment->voucher_no }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1.5 bg-red-100 text-red-800 rounded-lg font-bold text-sm border border-red-200">
                                        RM {{ number_format((float)$adjustment->amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $adjustment->note ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="mt-4 text-lg font-medium text-gray-500">No adjustments recorded yet</p>
                                    <p class="mt-2 text-sm text-gray-400">Adjustments appear here when balance is added</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($adjustments->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $adjustments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Auto-set datetime to now for payment and adjustment forms
    window.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        const datetimeLocal = now.toISOString().slice(0, 16);
        
        const paymentDateInput = document.getElementById('payment_paid_at');
        const adjustmentDateInput = document.getElementById('adjustment_adjusted_at');
        
        if (paymentDateInput && !paymentDateInput.value) {
            paymentDateInput.value = datetimeLocal;
        }
        
        if (adjustmentDateInput && !adjustmentDateInput.value) {
            adjustmentDateInput.value = datetimeLocal;
        }
    });
</script>
@endsection
