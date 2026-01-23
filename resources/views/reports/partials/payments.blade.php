<div class="overflow-x-auto">
    <table class="w-full print-table">
        <thead class="bg-green-50 dark:bg-green-900/20">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-green-700 dark:text-green-400 uppercase">Date</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-green-700 dark:text-green-400 uppercase">Debtor Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-green-700 dark:text-green-400 uppercase">Voucher No</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-green-700 dark:text-green-400 uppercase">Note</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-green-700 dark:text-green-400 uppercase">Amount</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($payments as $payment)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') }}
                </td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                    {{ $payment->debtor_name }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 font-mono">
                    {{ $payment->voucher_no ?? '-' }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                    {{ $payment->note ?? '-' }}
                </td>
                <td class="px-4 py-3 text-sm font-bold text-green-600 dark:text-green-400 text-right">
                    RM {{ number_format($payment->amount, 2) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No payment records found</td>
            </tr>
            @endforelse
        </tbody>
        @if($payments->count() > 0)
        <tfoot class="bg-green-50 dark:bg-green-900/20">
            <tr>
                <td colspan="4" class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white text-right">Total Payments:</td>
                <td class="px-4 py-3 text-sm font-bold text-green-700 dark:text-green-400 text-right">
                    RM {{ number_format($payments->sum('amount'), 2) }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

@if($payments->hasPages())
<div class="mt-6">
    {{ $payments->links() }}
</div>
@endif
