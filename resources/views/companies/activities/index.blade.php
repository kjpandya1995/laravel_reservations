<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                {{ __('Activities') }}
            </h2>

            <a href="{{ route('companies.activities.create', $company) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                + Create Activity
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl bg-white shadow">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">

                        {{-- Table Head --}}
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Image
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Name
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Start Time
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Price
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 w-72">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        {{-- Table Body --}}
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($company->activities as $activity)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Image --}}
                                    <td class="px-6 py-4">
                                        @if($activity->thumbnail)
                                            <img
                                                src="{{ asset('storage/' . $activity->thumbnail) }}"
                                                alt="{{ $activity->name }}"
                                                class="h-14 w-14 rounded-lg object-cover border shadow-sm"
                                            >
                                        @else
                                            <div class="h-14 w-14 rounded-lg bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                                                N/A
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Name --}}
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $activity->name }}
                                    </td>

                                    {{-- Start Time --}}
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $activity->start_time }}
                                    </td>

                                    {{-- Price --}}
                                    <td class="px-6 py-4 text-sm font-semibold text-indigo-600">
                                        ₹{{ number_format($activity->price, 2) }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-2">

                                            {{-- Edit --}}
                                            <a href="{{ route('companies.activities.edit', [$company, $activity]) }}"
                                               class="text-indigo-600 hover:text-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded">
                                                ✏️
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('companies.activities.destroy', [$company, $activity]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this activity?')">
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
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No activities found.
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
