<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">
                    Travel Gallery
                </h2>
                <p class="mt-1 text-sm text-indigo-600">
                    Explore stunning travel photos from around the world
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <select id="filter" class="block w-full pl-3 pr-10 py-2 text-base border-2 border-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg bg-white text-gray-700">
                        <option value="all">All Photos</option>
                        <option value="recent">Recent</option>
                        <option value="popular">Most Liked</option>
                        <option value="mine">My Photos</option>
                    </select>
                </div>
                <button type="button" id="uploadBtn" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Upload Photo
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Categories -->
            <div class="mb-8 flex flex-wrap gap-2">
                <button class="category-btn px-4 py-2 rounded-full bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition-colors duration-200" data-category="all">
                    All
                </button>
                <button class="category-btn px-4 py-2 rounded-full bg-purple-100 text-purple-700 hover:bg-purple-200 transition-colors duration-200" data-category="landscapes">
                    Landscapes
                </button>
                <button class="category-btn px-4 py-2 rounded-full bg-pink-100 text-pink-700 hover:bg-pink-200 transition-colors duration-200" data-category="cities">
                    Cities
                </button>
                <button class="category-btn px-4 py-2 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors duration-200" data-category="beaches">
                    Beaches
                </button>
                <button class="category-btn px-4 py-2 rounded-full bg-green-100 text-green-700 hover:bg-green-200 transition-colors duration-200" data-category="nature">
                    Nature
                </button>
            </div>

            <!-- Photo Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($photos as $photo)
                    <div class="group relative overflow-hidden rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl">
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden">
                            <img src="{{ $photo->path }}" 
                                alt="{{ $photo->caption }}" 
                                class="object-cover object-center w-full h-64 transform group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                <h3 class="text-lg font-semibold">{{ $photo->caption }}</h3>
                                <p class="text-sm opacity-90">{{ $photo->location }}</p>
                                <p class="text-xs opacity-80">By {{ $photo->entry->journal->user->name }}</p>
                                <div class="mt-3 flex items-center space-x-4">
                                    <button class="like-btn text-white hover:text-red-500 transition-colors duration-200" 
                                            data-photo-id="{{ $photo->id }}"
                                            data-liked="false">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                    <button class="comment-btn text-white hover:text-indigo-300 transition-colors duration-200" data-photo-id="{{ $photo->id }}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </button>
                                    <button class="share-btn text-white hover:text-indigo-300 transition-colors duration-200" data-photo-id="{{ $photo->id }}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No photos yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by uploading your first travel photo.</p>
                        <div class="mt-6">
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Upload Photo
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $photos->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Upload button functionality
            const uploadBtn = document.getElementById('uploadBtn');
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/*';
            fileInput.style.display = 'none';

            uploadBtn.addEventListener('click', () => {
                fileInput.click();
            });

            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    // Show loading state
                    uploadBtn.disabled = true;
                    uploadBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Uploading...
                    `;

                    const formData = new FormData();
                    formData.append('photo', this.files[0]);
                    formData.append('caption', prompt('Enter a caption for your photo:') || '');
                    formData.append('location', prompt('Enter the location where this photo was taken:') || '');
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                    fetch('{{ route("photos.upload") }}', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            alert('Photo uploaded successfully!');
                            // Reload the page to show the new photo
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Upload failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to upload photo: ' + error.message);
                    })
                    .finally(() => {
                        // Reset button state
                        uploadBtn.disabled = false;
                        uploadBtn.innerHTML = `
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Upload Photo
                        `;
                    });
                }
            });

            // Like button functionality
            const likeButtons = document.querySelectorAll('.like-btn');
            likeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const photoId = this.dataset.photoId;
                    const isLiked = this.dataset.liked === 'true';
                    const svg = this.querySelector('svg');
                    
                    // Toggle like state
                    this.classList.toggle('text-red-500');
                    svg.classList.toggle('fill-current');
                    this.dataset.liked = !isLiked;
                    
                    // Only make API call for non-sample photos
                    if (!photoId.startsWith('sample_')) {
                        fetch(`/photos/${photoId}/like`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                liked: !isLiked
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Like status updated:', data);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Revert the like state if the request failed
                            this.classList.toggle('text-red-500');
                            svg.classList.toggle('fill-current');
                            this.dataset.liked = isLiked;
                        });
                    }
                });
            });

            // Comment button functionality
            const commentButtons = document.querySelectorAll('.comment-btn');
            commentButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const photoId = this.dataset.photoId;
                    // Here you would typically show a comment modal or form
                    console.log('Show comments for photo:', photoId);
                });
            });

            // Share button functionality
            const shareButtons = document.querySelectorAll('.share-btn');
            shareButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const photoId = this.dataset.photoId;
                    // Here you would typically show a share modal with social media options
                    console.log('Share photo:', photoId);
                });
            });

            // Category filter functionality
            const categoryButtons = document.querySelectorAll('.category-btn');
            categoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const category = this.dataset.category;
                    
                    // Remove active class from all buttons
                    categoryButtons.forEach(btn => {
                        btn.classList.remove('bg-indigo-600', 'text-white');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('bg-indigo-600', 'text-white');
                    
                    // Here you would typically make an AJAX call to filter photos
                    console.log('Filter by category:', category);
                });
            });

            // Filter dropdown functionality
            const filter = document.getElementById('filter');
            filter.addEventListener('change', function() {
                const value = this.value;
                // Here you would typically make an AJAX call to filter photos
                console.log('Filter changed:', value);
            });
        });
    </script>
    @endpush
</x-app-layout> 