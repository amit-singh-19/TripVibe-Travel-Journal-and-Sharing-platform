<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->journals()
            ->withCount('entries')
            ->latest();

        // Search functionality
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by visibility
        if ($request->has('visibility')) {
            $query->where('is_public', $request->visibility === 'public');
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort functionality
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title':
                $query->orderBy('title');
                break;
            case 'entries':
                $query->orderBy('entries_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $journals = $query->paginate(9);

        // Get statistics for the dashboard
        $stats = [
            'total_journals' => auth()->user()->journals()->count(),
            'total_entries' => auth()->user()->journals()->withCount('entries')->get()->sum('entries_count'),
            'public_journals' => auth()->user()->journals()->where('is_public', true)->count(),
            'recent_activity' => auth()->user()->journals()
                ->with(['entries' => function($query) {
                    $query->latest()->take(3);
                }])
                ->get()
                ->pluck('entries')
                ->flatten()
                ->take(3),
        ];

        return view('dashboard', compact('journals', 'stats'));
    }
} 