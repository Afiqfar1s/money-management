@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Debtor Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $debtor->name }}</h2>
                <p class="text-gray-600 mt-1">{{ $debtor->description }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('debtors.edit', $debtor) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                    Edit
                </a>
                <form method="POST" action="{{ route('debtors.refresh', $debtor) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Refresh
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-6">
            <div>
                <div class="text-sm text-gray-600">Outstanding</div>
                @if ($debtor->outstanding > 0)
                    <div class="text-xl font-bold text-red-600">RM {{ number_format((float)$debtor->outstanding, 2) }}</div>
                @else
                    <div class="text-xl font-bold text-green-600">Settled</div>
                @endif
            </div>
            <div>
                <div class="text-sm text-gray-600">Starting Outstanding</div>
                <div class="text-xl font-bold text-gray-900">RM {{ number_format((float)$debtor->starting_outstanding, 2) }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Total Paid</div>
                <div class="text-xl font-bold text-green-600">RM {{ number_format((float)$total_paid, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Add Payment Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Add Payment</h3>
        <form method="POST" action="{{ route('payments.store', $debtor) }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="payment_amount" class="block text-sm font-medium text-gray-700">Amount (RM)</label>
                    <input type="number" name="amount" id="payment_amount" step="0.01" min="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="payment_paid_at" class="block text-sm font-medium text-gray-700">Paid Date/Time</label>
                    <input type="datetime-local" name="paid_at" id="payment_paid_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="payment_note" class="block text-sm font-medium text-gray-700">Note</label>
                    <input type="text" name="note" id="payment_note" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Add Payment
            </button>
        </form>
    </div>

    <!-- Add Existing Balance Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Add Existing Balance</h3>
        <form method="POST" action="{{ route('adjustments.store', $debtor) }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="adjustment_amount" class="block text-sm font-medium text-gray-700">Amount (RM)</label>
                    <input type="number" name="amount" id="adjustment_amount" step="0.01" min="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="adjustment_adjusted_at" class="block text-sm font-medium text-gray-700">Adjusted Date/Time</label>
                    <input type="datetime-local" name="adjusted_at" id="adjustment_adjusted_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="adjustment_note" class="block text-sm font-medium text-gray-700">Note</label>
                    <input type="text" name="note" id="adjustment_note" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                Add Adjustment
            </button>
        </form>
    </div>

    <!-- Payment History -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Payment History</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voucher No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($payments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->paid_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('payments.voucher', $payment) }}" class="text-indigo-600 hover:text-indigo-900" target="_blank">
                                    {{ $payment->voucher_no }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                RM {{ number_format((float)$payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $payment->note }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No payments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Adjustments History -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Balance Adjustments History</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adjusted Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($adjustments as $adjustment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $adjustment->adjusted_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-purple-600">
                                RM {{ number_format((float)$adjustment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $adjustment->note }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                No adjustments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $adjustments->links() }}
        </div>
    </div>
</div>

<script>
    // Prefill datetime fields with current datetime if empty on form submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const datetimeInputs = this.querySelectorAll('input[type="datetime-local"]');
            datetimeInputs.forEach(input => {
                if (!input.value) {
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const day = String(now.getDate()).padStart(2, '0');
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    input.value = `${year}-${month}-${day}T${hours}:${minutes}`;
                }
            });
        });
    });
</script>
@endsection
