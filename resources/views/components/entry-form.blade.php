@props(['journal', 'entry' => null])

<form method="POST" action="{{ $entry ? route('journals.entries.update', [$journal, $entry]) : route('journals.entries.store', $journal) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($entry)
        @method('PUT')
    @endif

    <div>
        <x-input-label for="date" :value="__('Date')" />
        <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', $entry?->date?->format('Y-m-d'))" required />
        <x-input-error class="mt-2" :messages="$errors->get('date')" />
    </div>

    <div>
        <x-input-label for="notes" :value="__('Notes')" />
        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('notes', $entry?->notes) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
    </div>

    <div>
        <x-input-label for="location" :value="__('Location')" />
        <div class="mt-1 flex rounded-md shadow-sm">
            <x-text-input id="location" name="location" type="text" class="flex-1 rounded-none rounded-l-md" :value="old('location', $entry?->location)" required placeholder="Enter a location" />
            <button type="button" class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm" onclick="openMapModal()">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('location')" />
    </div>

    <input type="hidden" id="latitude" name="latitude" :value="old('latitude', $entry?->latitude)">
    <input type="hidden" id="longitude" name="longitude" :value="old('longitude', $entry?->longitude)">

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

    <div>
        <x-input-label for="photos" :value="__('Photos')" />
        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600">
                    <label for="photos" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                        <span>Upload photos</span>
                        <input id="photos" name="photos[]" type="file" class="sr-only" multiple accept="image/*">
                    </label>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
            </div>
        </div>
        <div id="photo-preview" class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
        <x-input-error class="mt-2" :messages="$errors->get('photos') ?? []" />
    </div>

    <div class="flex items-center justify-end gap-4">
        <a href="{{ route('journals.show', $journal) }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
        <x-primary-button>{{ $entry ? __('Update Entry') : __('Create Entry') }}</x-primary-button>
    </div>
</form>

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

    // Photo preview
    document.getElementById('photos').addEventListener('change', function(e) {
        const preview = document.getElementById('photo-preview');
        preview.innerHTML = '';

        [...e.target.files].forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-40 object-cover rounded-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                        <input type="text" name="photo_captions[]" placeholder="Add caption" class="w-3/4 px-2 py-1 text-sm bg-white rounded">
                    </div>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush 