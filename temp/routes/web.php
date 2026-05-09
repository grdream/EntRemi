<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UpcomingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned the "web" middleware group.
|
*/

// Landing page: redirect authenticated users to dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// Dashboard — protected by auth + verified
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes — protected by auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Watchlist CRUD (slug-based, not ID)
    Route::get('/watchlist',                [ShowController::class, 'index'])->name('watchlist.index');
    Route::get('/watchlist/add',            [ShowController::class, 'create'])->name('watchlist.create');
    Route::post('/watchlist',               [ShowController::class, 'store'])->name('watchlist.store');
    Route::get('/watchlist/{slug}',         [ShowController::class, 'show'])->name('watchlist.show');
    Route::get('/watchlist/{slug}/edit',    [ShowController::class, 'edit'])->name('watchlist.edit');
    Route::put('/watchlist/{slug}',         [ShowController::class, 'update'])->name('watchlist.update');
    Route::delete('/watchlist/{slug}',      [ShowController::class, 'destroy'])->name('watchlist.destroy');

    // Episode routes (nested under show slug)
    Route::post('/watchlist/{slug}/episodes',              [EpisodeController::class, 'store'])->name('episodes.store');
    Route::post('/watchlist/{slug}/episodes/bulk',         [EpisodeController::class, 'bulkStore'])->name('episodes.bulk');
    Route::put('/watchlist/{slug}/episodes/{episode}',     [EpisodeController::class, 'update'])->name('episodes.update');
    Route::delete('/watchlist/{slug}/episodes/{episode}',  [EpisodeController::class, 'destroy'])->name('episodes.destroy');
    Route::post('/watchlist/{slug}/episodes/{episode}/toggle', [EpisodeController::class, 'toggleAired'])->name('episodes.toggle');

    // Schedule routes
    Route::post('/watchlist/{slug}/schedule',              [ScheduleController::class, 'upsert'])->name('schedule.upsert');
    Route::post('/watchlist/{slug}/schedule/deactivate',   [ScheduleController::class, 'deactivate'])->name('schedule.deactivate');
    Route::delete('/watchlist/{slug}/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');

    // Upcoming Episodes
    Route::get('/upcoming', [UpcomingController::class, 'index'])->name('upcoming.index');
});

require __DIR__.'/auth.php';
