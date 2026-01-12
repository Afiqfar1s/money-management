<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-semibold">Select Company</h1>
                    <p class="mt-2 text-sm text-gray-600">Choose which company you want to work on. Your dashboard and all debtor data will be scoped to this selection.</p>

                    @if (session('error'))
                        <div class="mt-4 rounded-md bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('companies.switch') }}" class="mt-6">
                        @csrf

                        <label class="block text-sm font-medium text-gray-700">Company</label>
                        <select name="company_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="" disabled {{ empty($currentCompanyId) ? 'selected' : '' }}>-- Select --</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" {{ (string) $currentCompanyId === (string) $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('company_id')" class="mt-2" />

                        <div class="mt-5 flex items-center gap-3">
                            <x-primary-button>Continue</x-primary-button>
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
