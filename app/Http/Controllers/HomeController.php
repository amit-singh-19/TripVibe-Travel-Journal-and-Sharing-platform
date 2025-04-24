<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     */
    public function index()
    {
        $featuredJournals = Journal::where('is_public', true)
            ->with(['user', 'photos'])
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('featuredJournals'));
    }
} 