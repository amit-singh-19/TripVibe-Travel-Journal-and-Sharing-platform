<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class JournalController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $journals = Journal::where('is_public', true)
            ->with(['user', 'photos'])
            ->latest()
            ->paginate(9);

        return view('journals.index', compact('journals'));
    }

    public function dashboard()
    {
        $journals = Auth::user()->journals()
            ->with(['entries', 'photos'])
            ->latest()
            ->paginate(9);

        return view('journals.dashboard', compact('journals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('journals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'location' => 'required|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'is_public' => 'nullable',
                'cover_image' => 'nullable|image|max:2048',
            ]);

            // Convert is_public checkbox value to boolean
            $validated['is_public'] = $request->boolean('is_public');

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('journals/cover-images', 'public');
                $validated['cover_image'] = $path;
            }

            // Create the journal
            $journal = Auth::user()->journals()->create($validated);

            // If the journal is public, create a photo entry for the gallery
            if ($journal->is_public && $journal->cover_image) {
                $journal->photos()->create([
                    'path' => $journal->cover_image,
                    'caption' => $journal->title,
                    'user_id' => Auth::id()
                ]);
            }

            return redirect()->route('journals.show', $journal)
                ->with('success', 'Journal created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create journal: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Journal $journal)
    {
        // Check if the journal is public or belongs to the current user
        if (!$journal->is_public && $journal->user_id !== Auth::id()) {
            abort(403);
        }

        // Load related data
        $journal->load(['entries', 'photos', 'user']);

        return view('journals.show', compact('journal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Journal $journal)
    {
        $this->authorize('update', $journal);

        return view('journals.edit', compact('journal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Journal $journal)
    {
        $this->authorize('update', $journal);

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'location' => 'required|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'is_public' => 'nullable',
                'cover_image' => 'nullable|image|max:2048',
            ]);

            // Convert is_public checkbox value to boolean
            $validated['is_public'] = $request->boolean('is_public');

            if ($request->hasFile('cover_image')) {
                if ($journal->cover_image) {
                    Storage::disk('public')->delete($journal->cover_image);
                }
                $validated['cover_image'] = $request->file('cover_image')->store('journals/cover-images', 'public');
            }

            $journal->update($validated);

            // If the journal is public and has a cover image, ensure it's in the photos
            if ($journal->is_public && $journal->cover_image) {
                $existingPhoto = $journal->photos()->where('path', $journal->cover_image)->first();
                if (!$existingPhoto) {
                    $journal->photos()->create([
                        'path' => $journal->cover_image,
                        'caption' => $journal->title,
                        'user_id' => Auth::id()
                    ]);
                }
            }

            return redirect()->route('journals.show', $journal)
                ->with('success', 'Journal updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update journal: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Journal $journal)
    {
        $this->authorize('delete', $journal);

        if ($journal->cover_image) {
            Storage::disk('public')->delete($journal->cover_image);
        }

        $journal->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Journal deleted successfully.');
    }
}
