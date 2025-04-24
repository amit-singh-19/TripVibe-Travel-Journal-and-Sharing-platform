<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $journal->title }}
            </h2>
            <div class="flex space-x-4">
                @if($journal->user_id === auth()->id())
                    <a href="{{ route('journals.edit', $journal) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Edit Journal
                    </a>
                @endif
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Journal Header -->
                    <div class="mb-8">
                        @if($journal->cover_image)
                            <img src="{{ Storage::url($journal->cover_image) }}" alt="{{ $journal->title }}" class="w-full h-64 object-cover rounded-lg mb-4">
                        @endif
                        
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">{{ $journal->title }}</h1>
                                <p class="text-sm text-gray-500">Created by {{ $journal->user->name }} on {{ $journal->created_at->format('F j, Y') }}</p>
                            </div>
                            @if($journal->is_public)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800" title="This journal is visible to everyone">
                                    Public
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800" title="This journal is only visible to you">
                                    Private
                                </span>
                            @endif
                        </div>

                        <div class="prose max-w-none">
                            <p class="text-gray-700">{{ $journal->description }}</p>
                        </div>
                    </div>

                    <!-- Journal Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Trip Details</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $journal->location }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $journal->start_date->format('F j, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">End Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $journal->end_date->format('F j, Y') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Map</h3>
                            <div id="map" class="h-64 rounded-lg"></div>
                        </div>
                    </div>

                    <!-- Entries Section -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Entries</h3>
                            <a href="{{ route('journals.entries.create', ['journal' => $journal]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Add Entry
                            </a>
                        </div>

                        @if($journal->entries->isEmpty())
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No entries yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating your first entry.</p>
                            </div>
                        @else
                            <div class="space-y-8">
                                @foreach($journal->entries as $entry)
                                    <div class="bg-white border rounded-xl p-6 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                        <div class="flex justify-between items-start">
                                            <div class="space-y-2">
                                                <div class="flex items-center space-x-2">
                                                    <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <p class="text-sm font-medium text-gray-900">{{ $entry->date->format('F j, Y') }}</p>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <p class="text-sm font-medium text-gray-900">{{ $entry->location }}</p>
                                                </div>
                                            </div>
                                            @if($entry->user_id === auth()->id())
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('journals.entries.edit', ['journal' => $journal, 'entry' => $entry]) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 border border-indigo-300 rounded-full text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors duration-200">
                                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('journals.entries.destroy', ['journal' => $journal, 'entry' => $entry]) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                onclick="return confirm('Are you sure you want to delete this entry?')"
                                                                class="inline-flex items-center px-3 py-1.5 border border-red-300 rounded-full text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-colors duration-200">
                                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-6 prose prose-indigo max-w-none">
                                            <div class="text-gray-700 whitespace-pre-line">{{ $entry->notes }}</div>
                                        </div>

                                        @if($entry->photos->isNotEmpty())
                                            <div class="mt-6">
                                                <h4 class="text-sm font-medium text-gray-900 mb-3">Photos</h4>
                                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                                    @foreach($entry->photos as $photo)
                                                        <div class="relative group aspect-square">
                                                            <img src="{{ Storage::url($photo->path) }}" 
                                                                 alt="{{ $photo->caption }}" 
                                                                 class="rounded-lg h-full w-full object-cover transform transition-transform duration-300 group-hover:scale-105">
                                                            @if($photo->caption)
                                                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-end p-3">
                                                                    <p class="text-white text-sm">{{ $photo->caption }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #map {
            height: 400px;
            width: 100%;
            border-radius: 0.5rem;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([{{ $journal->latitude }}, {{ $journal->longitude }}], 12);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([{{ $journal->latitude }}, {{ $journal->longitude }}])
                .addTo(map)
                .bindPopup('{{ $journal->location }}')
                .openPopup();
        });
    </script>
    @endpush
</x-app-layout> 