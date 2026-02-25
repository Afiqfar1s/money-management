<div class="mb-4">
    <p class="text-sm text-gray-600">Showing only debtors with outstanding balance > RM 0.00</p>
</div>

<div class="overflow-x-auto">
    <table class="w-full print-table">
        <thead class="bg-red-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-red-700 uppercase">#</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-red-700 uppercase">Debtor Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-red-700 uppercase">Type</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-red-700 uppercase">Contact</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-red-700 uppercase">Assigned To</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-red-700 uppercase">Outstanding Amount</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($debtors as $index => $debtor)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                <td class="px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $debtor->name }}</p>
                        @if($debtor->debtor_type == 'individual' && $debtor->ic_number)
                            <p class="text-xs text-gray-500">IC: {{ $debtor->ic_number }}</p>
                        @elseif($debtor->debtor_type == 'company' && $debtor->ssm_number)
                            <p class="text-xs text-gray-500">SSM: {{ $debtor->ssm_number }}</p>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3">
                    @if($debtor->debtor_type == 'individual')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Individual
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            Company
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    {{ $debtor->phone ?? $debtor->office_phone ?? '-' }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    {{ $debtor->user->name ?? 'Unassigned' }}
                </td>
                <td class="px-4 py-3 text-sm font-bold text-red-600 text-right">
                    RM {{ number_format($debtor->outstanding, 2) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-900 font-semibold">Great! No outstanding debts</p>
                        <p class="text-sm text-gray-500 mt-1">All debtors have been fully paid</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($debtors->count() > 0)
        <tfoot class="bg-red-50">
            <tr>
                <td colspan="5" class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">Total Outstanding:</td>
                <td class="px-4 py-3 text-sm font-bold text-red-700 text-right">
                    RM {{ number_format($debtors->sum('outstanding'), 2) }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
