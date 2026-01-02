@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Outstanding (Hutang)</div>
            <div class="text-2xl font-bold text-red-600">RM {{ number_format((float)$total_outstanding, 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Paid (Hutang Dah Dibayar)</div>
            <div class="text-2xl font-bold text-green-600">RM {{ number_format((float)$total_paid, 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Debtors (Semua User)</div>
            <div class="text-2xl font-bold text-gray-900">{{ $total_debtors }}</div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('debtors.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name or description..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="w-48">
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="owing" {{ request('status') == 'owing' ? 'selected' : '' }}>Owing</option>
                    <option value="settled" {{ request('status') == 'settled' ? 'selected' : '' }}>Settled</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Filter
            </button>
        </form>
    </div>

    <!-- Debtors Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outstanding</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Payment</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($debtors as $debtor)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('debtors.show', $debtor) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                {{ $debtor->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 truncate max-w-xs">{{ $debtor->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($debtor->outstanding > 0)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    RM {{ number_format((float)$debtor->outstanding, 2) }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Settled
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $debtor->payments_max_paid_at ? \Carbon\Carbon::parse($debtor->payments_max_paid_at)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('debtors.show', $debtor) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            <a href="{{ route('debtors.edit', $debtor) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                            <form method="POST" action="{{ route('debtors.refresh', $debtor) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900">Refresh</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No debtors found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $debtors->links() }}
    </div>
</div>
@endsection
