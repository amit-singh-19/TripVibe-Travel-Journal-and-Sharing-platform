@props(['journal' => null])

@if (session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

<form method="POST" action="{{ $journal ? route('journals.update', $journal) : route('journals.store') }}" enctype="multipart/form-data" class="space-y-6" id="journalForm">
    @csrf
    @if($journal)
        @method('PUT')
    @endif

    <div>
        <x-input-label for="title" :value="__('Title')" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $journal?->title)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('description', $journal?->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="start_date" :value="__('Start Date')" />
            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date', $journal?->start_date?->format('Y-m-d'))" required />
            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
        </div>

        <div>
            <x-input-label for="end_date" :value="__('End Date')" />
            <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date', $journal?->end_date?->format('Y-m-d'))" required />
            <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
        </div>
    </div>

    <div>
        <x-input-label for="location" :value="__('Location')" />
        <div class="mt-1 relative">
            <x-text-input id="location" name="location" type="text" class="block w-full" :value="old('location', $journal?->location)" required />
            <button type="button" onclick="openMapModal()" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('location')" />
    </div>

    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $journal?->latitude) }}">
    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $journal?->longitude) }}">

    <div>
        <x-input-label for="cover_image" :value="__('Cover Image')" />
        <input id="cover_image" name="cover_image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
        @if($journal?->cover_image)
            <div class="mt-2">
                <img src="{{ Storage::url($journal->cover_image) }}" alt="Current cover image" class="h-32 w-auto object-cover rounded-md">
            </div>
        @endif
    </div>

    <div class="flex items-center">
        <input id="is_public" name="is_public" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('is_public', $journal?->is_public) ? 'checked' : '' }}>
        <label for="is_public" class="ml-2 block text-sm text-gray-900">
            {{ __('Make this journal public') }}
        </label>
    </div>

    <div class="flex items-center justify-end gap-4">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
        <x-primary-button type="submit">{{ $journal ? __('Update Journal') : __('Create Journal') }}</x-primary-button>
    </div>
</form>

<!-- Map Modal -->
<div id="mapModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Select Location</h3>
            <button onclick="closeMapModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="map" class="w-full h-96"></div>
        <div class="mt-4 flex justify-end">
            <button type="button" onclick="closeMapModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                Cancel
            </button>
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

    function initMap() {
        // Initialize the map
        map = L.map('map').setView([0, 0], 2);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add marker
        marker = L.marker([0, 0], {
            draggable: true
        }).addTo(map);

        // Handle marker drag end
        marker.on('dragend', function() {
            const latLng = marker.getLatLng();
            updateLocationInputs(latLng.lat, latLng.lng);
        });

        // Handle map click
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateLocationInputs(e.latlng.lat, e.latlng.lng);
        });

        // Try to get current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const center = [position.coords.latitude, position.coords.longitude];
                map.setView(center, 15);
                marker.setLatLng(center);
                updateLocationInputs(position.coords.latitude, position.coords.longitude);
            });
        }
    }

    function updateLocationInputs(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // Use Nominatim for reverse geocoding
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('location').value = data.display_name;
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function openMapModal() {
        document.getElementById('mapModal').classList.remove('hidden');
        if (!map) {
            initMap();
        }
    }

    function closeMapModal() {
        document.getElementById('mapModal').classList.add('hidden');
    }
</script>
@endpush 