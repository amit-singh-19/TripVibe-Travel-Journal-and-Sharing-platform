<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Journal;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EntryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Journal $journal)
    {
        $this->authorize('create', [Entry::class, $journal]);

        return view('entries.create', compact('journal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Journal $journal)
    {
        $this->authorize('create', [Entry::class, $journal]);

        $validated = $request->validate([
            'date' => 'required|date',
            'notes' => 'required|string',
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photos.*' => 'nullable|image|max:2048',
            'photo_captions.*' => 'nullable|string|max:255',
        ]);

        $entry = $journal->entries()->create([
            'date' => $validated['date'],
            'notes' => $validated['notes'],
            'location' => $validated['location'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'user_id' => auth()->id(),
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('entries/photos', 'public');
                $entry->photos()->create([
                    'path' => $path,
                    'caption' => $validated['photo_captions'][$index] ?? null,
                    'journal_id' => $journal->id,
                ]);
            }
        }

        return redirect()->route('journals.show', $journal)
            ->with('success', 'Entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Journal $journal, Entry $entry)
    {
        $this->authorize('update', $entry);
        return view('entries.edit', compact('journal', 'entry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Journal $journal, Entry $entry)
    {
        $this->authorize('update', $entry);

        $validated = $request->validate([
            'date' => 'required|date',
            'notes' => 'required|string',
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photos.*' => 'nullable|image|max:2048',
            'photo_captions.*' => 'nullable|string|max:255',
        ]);

        $entry->update([
            'date' => $validated['date'],
            'notes' => $validated['notes'],
            'location' => $validated['location'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        // Update existing photo captions
        if ($request->has('photo_captions')) {
            foreach ($request->photo_captions as $index => $caption) {
                if (isset($entry->photos[$index])) {
                    $entry->photos[$index]->update(['caption' => $caption]);
                }
            }
        }

        // Handle new photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('entries/photos', 'public');
                $entry->photos()->create([
                    'path' => $path,
                    'caption' => $validated['photo_captions'][$index] ?? null,
                    'journal_id' => $journal->id,
                ]);
            }
        }

        return redirect()->route('journals.show', $journal)
            ->with('success', 'Entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Journal $journal, Entry $entry)
    {
        $this->authorize('delete', $entry);

        // Delete associated photos from storage
        foreach ($entry->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }

        $entry->delete();

        return redirect()->route('journals.show', $journal)
            ->with('success', 'Entry deleted successfully.');
    }
}
