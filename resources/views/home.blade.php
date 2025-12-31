<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="container">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                        @if($activities->count())
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                        @foreach($activities as $activity)

                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="{{ route('activity.show', $activity) }}" class="block">
                                <img src="{{ $activity->thumbnail }}" 
                                     alt="{{ $activity->name }}" 
                                     class="w-full h-48 object-cover"
                                     onerror="this.src='{{ asset('no_image.svg') }}'; this.onerror=null;">
                            </a>
                            <div class="p-4">
                                <h2 class="text-lg font-semibold mb-2">
                                    <a href="{{ route('activity.show', $activity) }}" 
                                       class="text-gray-900 hover:text-blue-600 transition-colors">
                                        {{ $activity->name }}
                                    </a>
                                </h2>
                                <time class="text-sm text-gray-500">{{ $activity->start_time }}</time>
                            </div>
                        </div>
                         @endforeach

                            </div>
                                                <div class="mt-6">{{ $activities->links() }}</div>

                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">No activities available</p>
                        </div>
                    @endif
                </div>
            </div>
</div>
        </div>
    </div>
</x-app-layout>
