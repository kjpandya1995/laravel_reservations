<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold  text-gray-800">
            {{ $activity->name }}
        </h2>
    </x-slot>
 
    <div class="py-12">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-lg rounded-xl">
 {{-- Thumbnail --}}
                <img
                    src="{{ $activity->thumbnail }}"
                    alt="{{ $activity->name }}"
                    class="w-full h-72 object-cover"
                >

                <div class="p-8  space-y-6">
                    @if(auth()->user()?->activities->contains($activity)) {{-- [tl! add:start] --}}
                        <div class="mb-6 bg-indigo-100 p-4 font-semibold text-indigo-700">You have already registered.</div>
                    @else
                        <form action="{{ route('activities.register', $activity) }}" method="POST">
                            @csrf
            
                            <x-secondary-button type="submit">
                                Register to Activity
                            </x-secondary-button>
                        </form>
                    @endif {{-- [tl! add:end] --}}


                    <!-- <img src="{{ $activity->thumbnail }}" alt="{{ $activity->name }}"> -->
                    <div class="flex flex-wrap items-center gap-6 text-gray-700">
                        <div class="text-xl font-bold text-indigo-600">
                            ₹{{ number_format($activity->price, 2) }}
                        </div>
                        <div class="flex items-center gap-2">
                            🕒
                            <time class="font-medium">
                                {{ $activity->start_time }}
                            </time>
                        </div>
                    </div>
                     {{-- Company --}}
                    <div class="text-sm text-gray-500">
                        Organized by
                        <span class="font-semibold text-gray-800">
                            {{ $activity->company->name }}
                        </span>
                    </div>

                    {{-- Description --}}
                    <div class="prose max-w-none text-gray-700">
                        <p>{{ $activity->description }}</p>
                    </div>
                    <!-- <h2>${{ $activity->price }}</h2>
                    <time>{{ $activity->start_time }}</time> -->
                    <!-- <div>Company: {{ $activity->company->name }}</div>
                    <p>{{ $activity->description }}</p> -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>