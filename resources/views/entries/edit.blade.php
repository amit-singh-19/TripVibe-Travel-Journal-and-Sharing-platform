<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Entry
            </h2>
            <a href="{{ route('journals.show', $journal) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Back to Journal
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('journals.entries.update', ['journal' => $journal, 'entry' => $entry]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', $entry->date->format('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('date')" />
                        </div>

                        <div>
                            <x-input-label for="notes" :value="__('Notes')" />
                            <x-textarea-input id="notes" name="notes" class="mt-1 block w-full" rows="4" required>{{ old('notes', $entry->notes) }}</x-textarea-input>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>

                        <div>
                            <x-input-label for="location" :value="__('Location')" />
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <x-text-input id="location" name="location" type="text" class="block w-full rounded-r-none" :value="old('location', $entry->location)" required />
                                <button type="button" onclick="openMapModal()" class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-300 rounded-r-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('location')" />
                        </div>

                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $entry->latitude) }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $entry->longitude) }}">

                        <div>
                            <x-input-label for="photos" :value="__('Photos')" />
                            <x-file-input id="photos" name="photos[]" class="mt-1 block w-full" multiple accept="image/*" />
                            <x-input-error class="mt-2" :messages="$errors->get('photos')" />
                        </div>

                        <div id="photo-captions-container" class="space-y-4">
                            @foreach($entry->photos as $index => $photo)
                                <div class="flex items-center space-x-4">
                                    <img src="{{ Storage::url($photo->path) }}" alt="Photo {{ $index + 1 }}" class="h-20 w-20 object-cover rounded-lg">
                                    <div class="flex-1">
                                        <x-input-label :value="__('Caption for Photo ' . ($index + 1))" />
                                        <x-text-input type="text" name="photo_captions[]" class="mt-1 block w-full" :value="old('photo_captions.' . $index, $photo->caption)" />
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Update Entry') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Modal -->
    <div id="mapModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full">
                <div class="p-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Select Location</h3>
                    <button type="button" onclick="closeMapModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <div id="map" class="h-96 w-full rounded-lg"></div>
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
        let map;
        let marker;

        function openMapModal() {
            document.getElementById('mapModal').classList.remove('hidden');
            initMap();
        }

        function closeMapModal() {
            document.getElementById('mapModal').classList.add('hidden');
        }

        function initMap() {
            if (!map) {
                const latitude = parseFloat(document.getElementById('latitude').value) || 0;
                const longitude = parseFloat(document.getElementById('longitude').value) || 0;
                
                map = L.map('map').setView([latitude, longitude], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                if (latitude && longitude) {
                    marker = L.marker([latitude, longitude]).addTo(map);
                }

                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([lat, lng]).addTo(map);
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                    // Reverse geocode to get location name
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('location').value = data.display_name;
                        });
                });
            }
        }
    </script>
    @endpush
</x-app-layout> 