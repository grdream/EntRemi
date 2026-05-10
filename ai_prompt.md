# EntRemi WatchList Reminder — Complete Fix Prompt for AI

Use the following prompt to send to an AI to complete all remaining fixes and features:

---

## COMPLETE PROMPT TO SEND TO AI:

```
You are working on the EntRemi WatchList Reminder Laravel application located at:
d:\GR WEB DEVS\TOOLS BUILD\EntRemi\temp

CONTEXT: This is a Laravel 13 app using Blade, Livewire 3, Alpine.js, TailwindCSS 3 with a 
premium dark/glassmorphism UI. The following critical bugs have already been fixed:
✅ buildMailer() added to UserSmtpSetting model
✅ SendEpisodeReminder job field bugs fixed (api_url→gateway_url, extra_parameters→extra_params)
✅ NotificationLog::create() now written from SendEpisodeReminder job  
✅ EpisodeReminderMail blade template created at resources/views/emails/episode-reminder.blade.php
✅ Admin notifications view field names fixed (season_number→season_no, episode_number→episode_no)
✅ upcoming/index.blade.php view created with Today/Week/Month/All tabs
✅ settings/notifications.blade.php created with ManageSmtp/ManageSms embedded
✅ SettingsController created with testSmtp/testSms endpoints
✅ Sidebar "Notifications" link → /settings/notifications, "Settings" → /profile
✅ public/hot file deleted (was causing Vite dev server lookup instead of production build)
✅ Scheduler interval fixed: everyMinute → everyFifteenMinutes
✅ no-poster.svg created at public/images/no-poster.svg

REMAINING TASKS TO COMPLETE (in priority order):

=== CRITICAL BACKEND FIXES ===

1. FIX Schedule::summaryLabel() method - Open app/Models/Schedule.php and verify this method 
   exists. If not, add it. It should return a human-readable string like 
   "Weekly on Monday, Wednesday at 8:00 PM" based on pattern, days_of_week, and air_time fields.

2. FIX ProfileController::update() - Currently it does NOT handle email_notifications and 
   sms_notifications toggle fields. Open app/Http/Controllers/ProfileController.php and add 
   handling for: email_notifications, sms_notifications (boolean), phone (string), 
   timezone (string), avatar (file upload).

3. FIX ManageSmtp Livewire - Open app/Livewire/ManageSmtp.php. The mount() method should 
   populate form fields from the existing UserSmtpSetting record if it exists. Currently 
   it doesn't pre-populate on load.

4. FIX ManageSms Livewire - Same issue. Open app/Livewire/ManageSms.php and ensure mount() 
   pre-populates existing UserSmsSetting values.

5. ADD ScheduleEngine auto-call in ScheduleController::upsert() - Open 
   app/Http/Controllers/ScheduleController.php. After saving the schedule, call 
   App\Services\ScheduleEngine::generateEpisodes($schedule) to auto-generate episode dates.

=== HIGH PRIORITY UI FIXES ===

6. ADD custom TailwindCSS pagination view - Create resources/views/vendor/pagination/tailwind.blade.php 
   with a styled pagination that matches the dark glassmorphism design system (dark backgrounds, 
   brand-500 active page, surface-800 buttons). Register it in AppServiceProvider with 
   Paginator::defaultView('vendor.pagination.tailwind').

7. COMPLETE admin/notifications.blade.php UI - Add:
   - Filter by status (sent/failed/pending) at top
   - Summary stats bar (total sent, total failed today)
   - Export CSV button
   Make it look like a proper enterprise observability dashboard.

8. IMPROVE tools/import.blade.php - Add:
   - JSON format example/documentation section
   - Better error display showing per-record import failures
   - Drag-and-drop file upload zone (Alpine.js)
   - Import result summary (X imported, Y failed, Z skipped)

9. ADD notification history to profile/user area - Create a "My Notifications" tab or section 
   showing the user's own notification_logs with status, channel, show name, time.
   Route: GET /notifications/history → view with paginated logs for auth user only.

10. FIX WatchlistGrid pagination styling - The pagination in watchlist.index currently uses 
    default unstyled Laravel pagination. Apply the custom view from task 6.

=== MEDIUM PRIORITY FEATURES ===

11. ADD trending/recommendations to Add Show page - In resources/views/watchlist/create.blade.php, 
    add a "Trending Now" section below the search area. Call JikanService::trending() and 
    TmdbService::trending() and display top 6 results as clickable cards that pre-fill the 
    search. Display only if user hasn't searched yet.

12. ADD TMDB API key status indicator - In the ShowSearch Livewire component and its view, 
    show a notice "TMDB not configured — Jikan only" badge when env('TMDB_API_KEY') is empty, 
    and hide TMDB tab.

13. IMPLEMENT dark mode preference in DB - Add a 'dark_mode' boolean column to users table 
    (new migration). Store toggle state in DB on profile save. Use it as default in app.blade.php 
    x-data initialization instead of just localStorage.

14. ADD show notes feature - WatchlistNote model exists but has no UI. On watchlist/{slug} 
    Overview tab, add a "Personal Notes" card with a textarea to add/edit/delete notes for 
    each show. Wire to a simple form that POSTs to a new NoteController.

15. ADD reminder configuration UI - The reminders table exists but is never populated. Add a 
    "Reminders" card on watchlist/{slug} show detail page. Let user set 
    remind_before_minutes (30, 60, 120, 1440) and channels (email, sms). Create 
    ReminderController with store/update/destroy and integrate into CheckUpcomingEpisodes command.

=== PRODUCTION READINESS ===

16. UPDATE README.md with full production setup:
    - PHP artisan commands for first deploy
    - Cron job setup for scheduler (*/15 * * * * php /path/to/artisan schedule:run)
    - Supervisor worker config for queue
    - MySQL configuration instructions
    - .env production variables

17. RUN php artisan optimize and ensure no errors.

=== DESIGN QUALITY REQUIREMENTS ===
The UI is already rated 9.8/10. Maintain this quality:
- All new views must use glass-card class for cards
- Use btn-primary for primary buttons, btn-secondary for secondary
- Use input-enhanced class for all form inputs
- Maintain dark-first design with dark: prefixes
- Add micro-animations on new interactive elements (hover:-translate-y-1)
- Use the established brand-500 (indigo), accent-500 (violet), surface-* palette
- Icons: use Heroicons SVG inline (same style as existing views)
- Flash messages must auto-dismiss after 3.5 seconds using Alpine.js x-init setTimeout

TECH NOTES:
- PHP: 8.3, Laravel 13, Livewire 3.x
- DB: SQLite for dev (database/database.sqlite exists with data)  
- Queue driver: database
- Assets: Vite production build (public/build/ exists, DO NOT start Vite dev server)
- DO NOT delete public/hot if it exists, instead reference the build manifest
- All Livewire components use wire:model.live for real-time updates
- Custom CSS classes are in resources/css/app.css
- TailwindCSS config is in tailwind.config.js with brand/accent/surface custom colors

Start with tasks 1-5 (critical backend fixes), then 6-10 (high priority UI), then continue.
After each major change, run: php artisan config:clear && php artisan route:clear
```
