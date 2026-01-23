<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Debtors -->
    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-lg p-6 border border-indigo-200 dark:border-indigo-800 print-summary-box">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 print-summary-label">Total Debtors</p>
                <p class="text-3xl font-bold text-indigo-900 dark:text-indigo-300 mt-2 print-summary-value">{{ $data['total_debtors'] }}</p>
            </div>
            <div class="h-12 w-12 bg-indigo-200 dark:bg-indigo-900 rounded-full flex items-center justify-center no-print">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Outstanding -->
    <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg p-6 border border-red-200 dark:border-red-800 print-summary-box">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-red-600 dark:text-red-400 print-summary-label">Total Outstanding</p>
                <p class="text-2xl font-bold text-red-900 dark:text-red-300 mt-2 print-summary-value">RM {{ number_format($data['total_outstanding'], 2) }}</p>
            </div>
            <div class="h-12 w-12 bg-red-200 dark:bg-red-900 rounded-full flex items-center justify-center no-print">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Paid -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-6 border border-green-200 dark:border-green-800 print-summary-box">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-green-600 dark:text-green-400 print-summary-label">Total Paid</p>
                <p class="text-2xl font-bold text-green-900 dark:text-green-300 mt-2 print-summary-value">RM {{ number_format($data['total_paid'], 2) }}</p>
            </div>
            <div class="h-12 w-12 bg-green-200 dark:bg-green-900 rounded-full flex items-center justify-center no-print">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Payments -->
<div>
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Payments (Last 10)</h3>
    <div class="overflow-x-auto">
        <table class="w-full print-table">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Debtor</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Voucher No</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($data['recent_payments'] as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $payment->debtor_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $payment->voucher_no ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-green-600 dark:text-green-400 text-right">
                        RM {{ number_format($payment->amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No recent payments found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Print-only Signature Section -->
<div class="print-only print-signature">
    <div style="display: flex; justify-content: space-between; margin-top: 60px;">
        <div>
            <div class="print-signature-line">Prepared By</div>
            <p style="margin-top: 5px; font-size: 12px;">{{ Auth::user()->name }}</p>
        </div>
        <div>
            <div class="print-signature-line">Approved By</div>
            <p style="margin-top: 5px; font-size: 12px;">_____________________</p>
        </div>
    </div>
</div>
