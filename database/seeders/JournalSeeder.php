<?php

namespace Database\Seeders;

use App\Models\Journal;
use App\Models\Entry;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class JournalSeeder extends Seeder
{
    public function run(): void
    {
        // Get the test user
        $user = User::where('email', 'test@example.com')->first();

        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Create sample journals
        $journals = [
            [
                'title' => 'European Adventure',
                'description' => 'A journey through Europe\'s most beautiful cities',
                'start_date' => '2024-05-01',
                'end_date' => '2024-05-15',
                'location' => 'Paris, France',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'is_public' => true,
            ],
            [
                'title' => 'Asian Expedition',
                'description' => 'Exploring the rich culture and history of Asia',
                'start_date' => '2024-06-01',
                'end_date' => '2024-06-20',
                'location' => 'Tokyo, Japan',
                'latitude' => 35.6762,
                'longitude' => 139.6503,
                'is_public' => true,
            ],
        ];

        foreach ($journals as $journalData) {
            $journal = Journal::create([
                ...$journalData,
                'user_id' => $user->id,
            ]);

            // Create sample entries for each journal
            $entries = [
                [
                    'date' => $journal->start_date,
                    'notes' => 'First day of the journey. Excited to explore!',
                    'location' => $journal->location,
                    'latitude' => $journal->latitude,
                    'longitude' => $journal->longitude,
                ],
                [
                    'date' => $journal->start_date->addDays(1),
                    'notes' => 'Visited local landmarks and tried traditional cuisine.',
                    'location' => $journal->location,
                    'latitude' => $journal->latitude,
                    'longitude' => $journal->longitude,
                ],
            ];

            foreach ($entries as $entryData) {
                $entry = Entry::create([
                    ...$entryData,
                    'journal_id' => $journal->id,
                ]);

                // Create sample photos for each entry
                Photo::create([
                    'path' => 'sample-photos/placeholder.jpg',
                    'caption' => 'Beautiful view from the first day',
                    'journal_id' => $journal->id,
                    'entry_id' => $entry->id,
                ]);
            }
        }
    }
} 