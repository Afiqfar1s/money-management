@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ debtorType: '{{ old('debtor_type', 'individual') }}' }">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Add New Debtor</h2>

        <form method="POST" action="{{ route('debtors.store') }}" class="space-y-6">
            @csrf

            <!-- Debtor Type Selection -->
            <div>
                <label for="debtor_type" class="block text-sm font-medium text-gray-700">Debtor Type *</label>
                <select name="debtor_type" id="debtor_type" x-model="debtorType" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="individual">Individual / Staff</option>
                    <option value="company">Company</option>
                </select>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                    <span x-show="debtorType === 'individual'">Name *</span>
                    <span x-show="debtorType === 'company'">Company Name *</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
            </div>

            <!-- Individual/Staff Fields -->
            <div x-show="debtorType === 'individual'" class="space-y-6 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900">Staff Information</h3>
                
                <div>
                    <label for="staff_number" class="block text-sm font-medium text-gray-700">Staff Number *</label>
                    <input type="text" name="staff_number" id="staff_number" value="{{ old('staff_number') }}" :required="debtorType === 'individual'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="ic_number" class="block text-sm font-medium text-gray-700">IC Number *</label>
                    <input type="text" name="ic_number" id="ic_number" value="{{ old('ic_number') }}" placeholder="e.g., 900101-01-1234" :required="debtorType === 'individual'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" placeholder="e.g., 012-345-6789" :required="debtorType === 'individual'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                    <input type="text" name="position" id="position" value="{{ old('position') }}" placeholder="e.g., Manager, Executive" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="start_working_date" class="block text-sm font-medium text-gray-700">Start Working Date *</label>
                    <input type="date" name="start_working_date" id="start_working_date" value="{{ old('start_working_date') }}" :required="debtorType === 'individual'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="resign_date" class="block text-sm font-medium text-gray-700">Resign Date</label>
                    <input type="date" name="resign_date" id="resign_date" value="{{ old('resign_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Leave empty if still working</p>
                </div>
            </div>

            <!-- Company Fields -->
            <div x-show="debtorType === 'company'" class="space-y-6 border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900">Company Information</h3>
                
                <div>
                    <label for="ssm_number" class="block text-sm font-medium text-gray-700">SSM Registration Number *</label>
                    <input type="text" name="ssm_number" id="ssm_number" value="{{ old('ssm_number') }}" placeholder="e.g., 202301234567" :required="debtorType === 'company'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="office_phone" class="block text-sm font-medium text-gray-700">Office Phone *</label>
                    <input type="text" name="office_phone" id="office_phone" value="{{ old('office_phone') }}" placeholder="e.g., 03-1234-5678" :required="debtorType === 'company'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="company_address" class="block text-sm font-medium text-gray-700">Company Address</label>
                    <textarea name="company_address" id="company_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('company_address') }}</textarea>
                </div>
            </div>

            <div class="border-t pt-6">
                <label for="starting_outstanding" class="block text-sm font-medium text-gray-700">Starting Outstanding (RM) *</label>
                <input type="number" name="starting_outstanding" id="starting_outstanding" value="{{ old('starting_outstanding', '0.00') }}" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="voucher_no" class="block text-sm font-medium text-gray-700">Voucher Number</label>
                <input type="text" name="voucher_no" id="voucher_no" value="{{ old('voucher_no') }}" placeholder="e.g., VCH-001, INV-001" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <p class="text-xs text-gray-500 mt-1">Optional: Reference number from your receipt/invoice</p>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Save
                </button>
                <a href="{{ route('debtors.index') }}" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
