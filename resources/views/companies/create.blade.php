<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-900">New Company</h1>
                        <a href="{{ route('companies.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                    </div>

                    <form method="POST" action="{{ route('companies.store') }}" class="mt-6 space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="name" value="Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="code" value="Code (optional)" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" :value="old('code')" />
                            <x-input-error class="mt-2" :messages="$errors->get('code')" />
                        </div>

                        <div>
                            <x-input-label for="phone" value="Phone (optional)" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <div>
                            <x-input-label for="address" value="Address (optional)" />
                            <textarea id="address" name="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>Create</x-primary-button>
                            <a href="{{ route('companies.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
