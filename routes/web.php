<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home page route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public routes
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Journal Routes
    Route::resource('journals', JournalController::class);
    
    // Entry Routes (nested under journals)
    Route::prefix('journals/{journal}')->group(function () {
        Route::get('entries/create', [EntryController::class, 'create'])->name('journals.entries.create');
        Route::post('entries', [EntryController::class, 'store'])->name('journals.entries.store');
        Route::get('entries/{entry}/edit', [EntryController::class, 'edit'])->name('journals.entries.edit');
        Route::put('entries/{entry}', [EntryController::class, 'update'])->name('journals.entries.update');
        Route::delete('entries/{entry}', [EntryController::class, 'destroy'])->name('journals.entries.destroy');
    });
    
    // Photo Routes
    Route::post('/photos/upload', [GalleryController::class, 'upload'])->name('photos.upload');
    Route::post('/photos/{photo}/like', [PhotoController::class, 'like'])->name('photos.like');
    
    // Gallery Routes
    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
    Route::post('/photos/{photo}/comment', [PhotoController::class, 'comment'])->name('photos.comment');
    
    // Map Routes
    Route::get('/map', [MapController::class, 'index'])->name('map');
    
    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
    
    // Favorites Routes
    Route::get('/favorites', function () {
        return view('favorites');
    })->name('favorites');
    Route::post('/journals/{journal}/favorite', [JournalController::class, 'favorite'])->name('journals.favorite');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
