<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UpcomingController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ReminderController;
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

use App\Http\Controllers\DashboardController;

// Dashboard — protected by auth + verified
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes — protected by auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Watchlist CRUD (slug-based, not ID)
    Route::get('/watchlist',                [ShowController::class, 'index'])->name('watchlist.index');
    Route::get('/watchlist/add',            [ShowController::class, 'create'])->name('watchlist.create');
    Route::post('/watchlist',               [ShowController::class, 'store'])->name('watchlist.store');
    Route::post('/watchlist/quick-add',     [ShowController::class, 'quickStore'])->name('watchlist.quick-add');
    Route::get('/watchlist/{slug}',         [ShowController::class, 'show'])->name('watchlist.show');
    Route::get('/watchlist/{slug}/edit',    [ShowController::class, 'edit'])->name('watchlist.edit');
    Route::put('/watchlist/{slug}',         [ShowController::class, 'update'])->name('watchlist.update');
    Route::delete('/watchlist/{slug}',      [ShowController::class, 'destroy'])->name('watchlist.destroy');

    // ─── Admin Routes (admin middleware) ──────────────────────────────────────
    Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
        Route::get('/',                          [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users',                     [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}/edit',         [\App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}',              [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}',           [\App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/settings',                  [\App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
        Route::post('/settings',                 [\App\Http\Controllers\AdminController::class, 'saveSettings'])->name('settings.save');
        Route::get('/notifications',             [\App\Http\Controllers\AdminController::class, 'notifications'])->name('notifications');
        Route::get('/notifications/export',      [\App\Http\Controllers\AdminController::class, 'exportCsv'])->name('notifications.export');
    });

    // Notification History (per-user)
    Route::get('/notifications/history', [\App\Http\Controllers\NotificationHistoryController::class, 'index'])->name('notifications.history');

    // Notes (per show)
    Route::post('/watchlist/{slug}/notes',         [NoteController::class, 'store'])->name('notes.store');
    Route::delete('/watchlist/{slug}/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // Reminders (per show)
    Route::post('/watchlist/{slug}/reminders',              [ReminderController::class, 'store'])->name('reminders.store');
    Route::put('/watchlist/{slug}/reminders/{reminder}',   [ReminderController::class, 'update'])->name('reminders.update');
    Route::delete('/watchlist/{slug}/reminders/{reminder}', [ReminderController::class, 'destroy'])->name('reminders.destroy');

    // Tools
    Route::get('/tools/import', [\App\Http\Controllers\ImportController::class, 'index'])->name('tools.import.index');
    Route::post('/tools/import', [\App\Http\Controllers\ImportController::class, 'store'])->name('tools.import.store');

    // Notification Settings (SMTP + SMS)
    Route::get('/settings/notifications', [\App\Http\Controllers\SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::post('/settings/smtp/test', [\App\Http\Controllers\SettingsController::class, 'testSmtp'])->name('settings.smtp.test');
    Route::post('/settings/sms/test', [\App\Http\Controllers\SettingsController::class, 'testSms'])->name('settings.sms.test');

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

// \u2500\u2500\u2500 Web Installer (locked after first install) \u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500
if (!file_exists(storage_path('installed.lock'))) {
    Route::get('/install',          [\App\Http\Controllers\InstallController::class, 'index'])->name('install.index');
    Route::post('/install/check-db', [\App\Http\Controllers\InstallController::class, 'checkDb'])->name('install.checkDb');
    Route::post('/install/run',     [\App\Http\Controllers\InstallController::class, 'run'])->name('install.run');
} else {
    Route::get('/install', fn() => redirect('/login'))->name('install.index');
}

require __DIR__.'/auth.php';
