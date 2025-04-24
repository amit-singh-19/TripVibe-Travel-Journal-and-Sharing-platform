<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Entry;
use App\Models\Journal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display the gallery page.
     */
    public function index()
    {
        // Get user-uploaded photos
        $userPhotos = Photo::with(['entry.journal.user'])
            ->whereHas('entry.journal', function ($query) {
                $query->where('is_public', true);
            })
            ->latest()
            ->get();

        // Sample travel images from Unsplash
        $sampleImages = [
            [
                'url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1353&q=80',
                'caption' => 'Beautiful Beach Sunset',
                'location' => 'Maldives'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'City Skyline at Dusk',
                'location' => 'New York'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1506744038136-462738349b48?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Mountain Landscape',
                'location' => 'Switzerland'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae51f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Tropical Paradise',
                'location' => 'Bali'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Desert Adventure',
                'location' => 'Sahara'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1507608616759-54f48f0af0ee?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Northern Lights',
                'location' => 'Iceland'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Tropical Beach',
                'location' => 'Thailand'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1506197603052-3cc9c3a201bd?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Historic City',
                'location' => 'Rome'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1518546305927-5a555bb7020d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Mountain Village',
                'location' => 'Nepal'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1518546305927-5a555bb7020d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Wildlife Safari',
                'location' => 'Kenya'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Coastal Road',
                'location' => 'California'
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'caption' => 'Urban Exploration',
                'location' => 'Tokyo'
            ]
        ];

        // Create a collection of sample photos
        $samplePhotos = collect($sampleImages)->map(function ($image, $index) {
            return (object)[
                'id' => 'sample_' . ($index + 1),
                'path' => $image['url'],
                'caption' => $image['caption'],
                'location' => $image['location'],
                'entry' => (object)[
                    'journal' => (object)[
                        'user' => (object)[
                            'name' => 'Travel Enthusiast'
                        ]
                    ]
                ]
            ];
        });

        // Combine user photos and sample photos
        $allPhotos = $userPhotos->map(function ($photo) {
            return (object)[
                'id' => $photo->id,
                'path' => Storage::url($photo->path),
                'caption' => $photo->caption,
                'location' => $photo->entry->location,
                'entry' => (object)[
                    'journal' => (object)[
                        'user' => (object)[
                            'name' => $photo->entry->journal->user->name
                        ]
                    ]
                ]
            ];
        })->concat($samplePhotos);

        // Paginate the combined photos
        $photos = new \Illuminate\Pagination\LengthAwarePaginator(
            $allPhotos->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), 12),
            $allPhotos->count(),
            12,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('gallery', compact('photos'));
    }

    /**
     * Handle photo upload.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            
            // Create a new entry for the photo
            $entry = Entry::create([
                'journal_id' => $request->journal_id ?? null,
                'date' => now(),
                'location' => $request->location,
                'notes' => $request->caption,
            ]);

            // Create the photo record
            $photo = Photo::create([
                'entry_id' => $entry->id,
                'path' => $path,
                'caption' => $request->caption,
            ]);

            return response()->json([
                'success' => true,
                'photo' => [
                    'id' => $photo->id,
                    'path' => Storage::url($photo->path),
                    'caption' => $photo->caption,
                    'location' => $entry->location,
                    'entry' => [
                        'journal' => [
                            'user' => [
                                'name' => auth()->user()->name
                            ]
                        ]
                    ]
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Photo upload failed'
        ], 400);
    }
} 