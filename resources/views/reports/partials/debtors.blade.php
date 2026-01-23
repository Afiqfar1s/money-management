<div class="overflow-x-auto">
    <table class="w-full print-table">
        <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">#</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Contact</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Assigned To</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Outstanding</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($debtors as $index => $debtor)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                <td class="px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $debtor->name }}</p>
                        @if($debtor->debtor_type == 'individual' && $debtor->ic_number)
                            <p class="text-xs text-gray-500 dark:text-gray-400">IC: {{ $debtor->ic_number }}</p>
                        @elseif($debtor->debtor_type == 'company' && $debtor->ssm_number)
                            <p class="text-xs text-gray-500 dark:text-gray-400">SSM: {{ $debtor->ssm_number }}</p>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3">
                    @if($debtor->debtor_type == 'individual')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                            Individual
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                            Company
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                    {{ $debtor->phone ?? $debtor->office_phone ?? '-' }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                    {{ $debtor->user->name ?? 'Unassigned' }}
                </td>
                <td class="px-4 py-3 text-sm font-semibold text-right {{ $debtor->outstanding > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">
                    RM {{ number_format($debtor->outstanding, 2) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No debtors found</td>
            </tr>
            @endforelse
        </tbody>
        @if($debtors->count() > 0)
        <tfoot class="bg-gray-50 dark:bg-gray-900">
            <tr>
                <td colspan="5" class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white text-right">Total Outstanding:</td>
                <td class="px-4 py-3 text-sm font-bold text-red-600 dark:text-red-400 text-right">
                    RM {{ number_format($debtors->sum('outstanding'), 2) }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
