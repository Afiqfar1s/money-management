<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Companies</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage tenant companies and user access</p>
                </div>
                <a href="{{ route('companies.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">New Company</a>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Users</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($companies as $company)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $company->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $company->code ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $company->users()->count() }}</td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <a href="{{ route('companies.edit', $company) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                    <form method="POST" action="{{ route('companies.destroy', $company) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-3 text-red-600 hover:text-red-800 font-medium" onclick="return confirm('Delete this company?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $companies->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
