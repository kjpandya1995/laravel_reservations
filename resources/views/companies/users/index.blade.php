<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                {{ __('Company Users') }}
            </h2>

            <a href="{{ route('companies.users.create', $company) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                + Add User
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl bg-white shadow">

                {{-- Table Wrapper --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">

                        {{-- Table Head --}}
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Name
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 w-56">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        {{-- Table Body --}}
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $user->name }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-3">

                                            {{-- Edit --}}
                                            <a href="{{ route('companies.users.edit', [$company, $user]) }}"
                                               class="text-indigo-600 hover:text-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded">
                                                ✏️ 
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('companies.users.destroy', [$company, $user]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to remove this user?')">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-red-600 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 rounded">
                                                    🗑️ 
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-10 text-center text-sm text-gray-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
