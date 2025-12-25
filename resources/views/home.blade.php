<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-4 gap-x-5 gap-y-8 mb-5">
                        <div class="row">

                        @forelse($activities as $activity)
                            <div class="col-md-3 mb-3">
                                 <a href="{{ route('activity.show', $activity) }}"> 
                                    <img src="{{ asset('storage/' . $activity->thumbnail) }}" alt="{{ $activity->name }}">
                                </a>
                                <!-- <img src="{{ asset($activity->thumbnail) }}" alt="{{ $activity->name }}"> -->
                                <h2>
                                    <a href="{{ route('activity.show', $activity) }}" class="text-lg font-semibold">{{ $activity->name }}</a> 
                                    <!-- <a href="#" class="text-lg font-semibold">{{ $activity->name }}</a>  -->
                                </h2>
                                <time>{{ $activity->start_time }}</time>
                            </div>
                            @empty
                            <p>No activities</p>
                            @endforelse
                        </div>
                    </div>
 
                    <div class="mt-6">{{ $activities->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
