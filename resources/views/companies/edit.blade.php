<x-app-layout>
    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">Edit Company</h1>
                            <p class="text-sm text-gray-600 mt-1">Manage company info and assign users</p>
                        </div>
                        <a href="{{ route('companies.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                    </div>

                    @if (session('success'))
                        <div class="mt-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('companies.update', $company) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="name" value="Name" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $company->name)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="code" value="Code" />
                                <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" :value="old('code', $company->code)" />
                                <x-input-error class="mt-2" :messages="$errors->get('code')" />
                            </div>

                            <div>
                                <x-input-label for="phone" value="Phone" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $company->phone)" />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <div>
                                <x-input-label for="address" value="Address" />
                                <textarea id="address" name="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $company->address) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('address')" />
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h2 class="text-lg font-semibold text-gray-900">Assign Users</h2>
                            <p class="text-sm text-gray-600 mt-1">Only assigned users can access this company in the dropdown.</p>

                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($users as $user)
                                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            {{ in_array($user->id, old('user_ids', $assignedUserIds)) ? 'checked' : '' }}>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-600">{{ $user->email }} @if($user->isAdmin()) • admin @endif</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>Save</x-primary-button>
                            <a href="{{ route('companies.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
