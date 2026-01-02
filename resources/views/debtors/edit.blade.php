@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Debtor</h2>

        <form method="POST" action="{{ route('debtors.update', $debtor) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Person or Company</label>
                <input type="text" name="name" id="name" value="{{ old('name', $debtor->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $debtor->description) }}</textarea>
            </div>

            <div>
                <label for="starting_outstanding" class="block text-sm font-medium text-gray-700">Starting Outstanding (RM)</label>
                <input type="number" name="starting_outstanding" id="starting_outstanding" value="{{ old('starting_outstanding', $debtor->starting_outstanding) }}" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Save
                </button>
                <a href="{{ route('debtors.show', $debtor) }}" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
