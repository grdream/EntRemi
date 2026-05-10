# WatchList Reminder — Multi-Phase Testing Plan
> **Project:** EntRemi · **Path:** `d:\GR WEB DEVS\TOOLS BUILD\EntRemi\temp`
> Each phase must be FULLY PASSED before proceeding to the next.

---

## Pre-Test Environment Checklist

```bash
# 1. Run migrations fresh
php artisan migrate:fresh --seed

# 2. Build assets
npm run build

# 3. Start dev server
php artisan serve --port=8000

# 4. Run queue worker (separate terminal)
php artisan queue:work --tries=3

# 5. Verify .env has required values
# - APP_KEY must be set
# - TMDB_API_KEY must be set for API tests
# - ADMIN_EMAIL must match a registered user email
```

---

## PHASE 1 — Environment & Foundation Tests

**Goal:** Verify the Laravel application boots correctly with all services registered.

### 1.1 Application Boot
- [ ] `php artisan config:clear && php artisan cache:clear` — no errors
- [ ] `php artisan route:list` — lists all expected routes (watchlist, upcoming, admin, tools, episodes, schedules, auth)
- [ ] `php artisan migrate:status` — all 11 migrations show `Ran`
- [ ] `php artisan queue:listen` — starts without errors

### 1.2 Landing Page Test (Browser)
```
URL: http://localhost:8000
```
- [ ] Page loads with dark hero section
- [ ] "Start Tracking Free" button leads to `/register`
- [ ] "Sign In" button leads to `/login`
- [ ] Dark mode toggle (sun/moon icon) switches theme
- [ ] Dark mode preference is saved (reload page → stays dark)
- [ ] Feature grid displays 6 cards
- [ ] Footer copyright year is current
- [ ] No console JavaScript errors (check DevTools)
- [ ] Mobile responsive — sidebar collapses at mobile width (375px)

### 1.3 Asset Build Test
- [ ] CSS loads (Tailwind classes render correctly)
- [ ] Alpine.js works (dark mode toggle functional without console errors)
- [ ] Vite manifest present: `public/build/manifest.json`

---

## PHASE 2 — Authentication Flow Tests

**Goal:** Complete auth cycle works, extended profile fields save correctly.

### 2.1 Registration
```
URL: http://localhost:8000/register
```
- [ ] Registration form loads with premium guest layout styling
- [ ] Submit with empty fields → validation errors shown under each field
- [ ] Submit with mismatched passwords → "Password confirmation does not match" error
- [ ] Submit with valid data (name, email, password x2) → redirects to `/dashboard`
- [ ] New user appears in `users` table (check via `php artisan tinker` → `App\Models\User::latest()->first()`)
- [ ] Email verification notice shown (if email verification enabled)

### 2.2 Login / Logout
```
URL: http://localhost:8000/login
```
- [ ] Login with registered user → redirects to `/dashboard`
- [ ] Login with wrong password → "These credentials do not match" error
- [ ] "Remember me" checkbox present
- [ ] Authenticated user visiting `/` redirects to `/dashboard`
- [ ] Logout button in topbar → redirects to `/` and clears session

### 2.3 User Profile
```
URL: http://localhost:8000/profile
```
- [ ] Profile page loads with all three sections (Update Info, Change Password, Delete Account)
- [ ] Update name and email → saves successfully, shows success flash
- [ ] Timezone dropdown populated with all PHP timezones (should show 400+ options)
- [ ] Phone number field accepts and saves numeric/international format
- [ ] Avatar upload: select a JPG/PNG (< 2MB) → saves to `storage/app/public/avatars/`, shown in sidebar
- [ ] Avatar upload: upload a PDF or large file → validation error shown
- [ ] Change password with wrong current password → error shown
- [ ] Change password successfully → session valid, can still navigate
- [ ] Delete account: cancel → no action. Confirm → account deleted, redirected to `/`

---

## PHASE 3 — Dashboard Tests

**Goal:** Dashboard stats are accurate and panels render correctly.

```
URL: http://localhost:8000/dashboard
```

### 3.1 Stats Cards
- [ ] "Total Shows" card shows correct count
- [ ] "Watching" card shows count of shows with status=`watching`
- [ ] "Airing Today" card shows count of unaired episodes with `air_datetime` = today
- [ ] "This Week" card shows count of unaired episodes in next 7 days
- [ ] All 4 cards have gradient icons and correct colors

### 3.2 Airing Today Panel
- [ ] Panel renders "Airing Today" header with icon
- [ ] Empty state message when no episodes today (not a blank/broken panel)
- [ ] When episodes exist: shows show title, episode number, air time in user's timezone

### 3.3 This Week Panel
- [ ] Right sidebar panel shows weekly episodes
- [ ] Empty state when no episodes this week

### 3.4 Quick Actions
- [ ] "Search & Add Shows" → navigates to `/watchlist/add`
- [ ] "Configure Notifications" → navigates to profile page (currently links to profile)

---

## PHASE 4 — Watchlist CRUD Tests

**Goal:** Full CRUD operations for shows work end-to-end.

### 4.1 Add Show — Manual Entry
```
URL: http://localhost:8000/watchlist/add
```
- [ ] Page loads with Show Search component (3 tabs visible: Search Online, Paste Link, Manual)
- [ ] Switch to "Manual" tab → form with Title, Type, Status, Description, Poster URL, etc.
- [ ] Submit empty title → validation error shown
- [ ] Submit valid manual show (title: "Test Drama", type: drama, status: watching) → redirects to show detail
- [ ] Show appears in database with auto-generated slug
- [ ] Show appears in watchlist grid at `/watchlist`

### 4.2 Add Show — TMDB Search
```
URL: http://localhost:8000/watchlist/add (Search Online tab)
```
- [ ] Type "Breaking Bad" in search → Livewire debounce triggers (after 300ms)
- [ ] Results appear with poster images, type badges (TV/Movie)
- [ ] Click "Add to Watchlist" on a result → show saved with TMDB data pre-filled
- [ ] Verify in database: `tmdb_id` set, `poster_url` set, `description` set
- [ ] Search with no TMDB API key set → graceful error message, not a white screen

### 4.3 Add Show — Jikan (Anime) Search
- [ ] Switch to Anime type → Jikan tab/option
- [ ] Search "Naruto" → results appear
- [ ] Add anime → `jikan_id` set in database

### 4.4 View Show Detail
```
URL: http://localhost:8000/watchlist/{slug}
```
- [ ] Overview tab: shows poster, synopsis, genres, stats, progress bar
- [ ] Episodes tab: empty state with "Add Episode" option
- [ ] Schedule tab: form to create schedule
- [ ] Back arrow → returns to `/watchlist`
- [ ] Status badge displays correctly colored
- [ ] Rating shown with star icon

### 4.5 Edit Show
```
URL: http://localhost:8000/watchlist/{slug}/edit
```
- [ ] Pre-populated with existing data
- [ ] Update title → saved, slug updated if needed
- [ ] Update status (watching → completed) → badge updates on detail page
- [ ] Cancel → no changes saved

### 4.6 Delete Show
- [ ] Delete button on show detail page
- [ ] Confirmation dialog appears
- [ ] Confirm → show removed from watchlist (soft deleted in DB)
- [ ] Show no longer appears in `/watchlist` grid

---

## PHASE 5 — Watchlist Grid & Filter Tests

```
URL: http://localhost:8000/watchlist
```

### 5.1 Grid Display
- [ ] Shows render as poster cards in a responsive grid (2→3→4→5→6 columns at breakpoints)
- [ ] Poster image loads or "No Poster" placeholder shown (no broken image icon)
- [ ] Status badge overlays top-left of poster
- [ ] Rating badge overlays top-right of poster (if rating set)
- [ ] Hover → overlay gradient + "View" and "Edit" buttons appear
- [ ] Episode count shown below title

### 5.2 Filters & Search
- [ ] Search "drama" → Livewire filters list in real-time (300ms debounce)
- [ ] Status filter "Watching" → only watching shows
- [ ] Type filter "Anime" → only anime shows
- [ ] Sort by "Title A–Z" → alphabetical order
- [ ] Combine search + status + type filters → intersection applied correctly
- [ ] Clear all filters → full list returns
- [ ] Loading spinner appears during Livewire request

### 5.3 Empty State
- [ ] Empty watchlist → "No shows found" with "Discover Shows" CTA
- [ ] Filtered to empty result → "No shows match your filters" with "Clear Filters" button

### 5.4 Pagination
- [ ] Add 15+ shows → pagination appears at bottom
- [ ] Pagination matches UI design (not default Laravel unstyled links)
- [ ] Page 2 loads correctly

---

## PHASE 6 — Episode Management Tests

```
URL: http://localhost:8000/watchlist/{slug} → Episodes Tab
```

### 6.1 Add Single Episode
- [ ] Episode Manager loads inside Episodes tab
- [ ] "Add Episode" form: Episode No., Season No., Title, Air Date/Time, Duration fields
- [ ] Submit single episode → episode appears in list below
- [ ] Episode shows air date/time in user's timezone

### 6.2 Bulk Add Episodes
- [ ] "Bulk Add" UI: enter count (e.g., 12) → generates 12 episode slots
- [ ] Slots auto-populate episode numbers
- [ ] Submit bulk → all 12 episodes appear in DB
- [ ] Episode count badge on tab updates

### 6.3 Toggle Aired Status
- [ ] Click "Mark Watched" on an unaired episode → `is_aired` = true
- [ ] Episode styling changes (strikethrough or green badge)
- [ ] Progress bar on Overview tab updates percentage
- [ ] Toggle back → resets to unwatched

### 6.4 Edit Episode
- [ ] Click edit on episode → inline edit form opens
- [ ] Change title, save → updated in list
- [ ] Change air date → updates correctly

### 6.5 Delete Episode
- [ ] Delete episode → confirmation
- [ ] Confirm → removed from list, count updated

---

## PHASE 7 — Schedule Engine Tests

```
URL: http://localhost:8000/watchlist/{slug} → Schedule Tab
```

### 7.1 Create Schedule
- [ ] Schedule form loads
- [ ] Select "Weekly" pattern → "Days of Week" checkboxes appear
- [ ] Select "Daily" pattern → Days of Week hidden
- [ ] Select "Monthly" pattern → Days of Week hidden
- [ ] Fill: Pattern=Weekly, Monday+Wednesday, Air Time=20:00, Timezone=UTC, Start Date=today
- [ ] Submit → schedule saved, appears in "Schedule History" section
- [ ] Schedule shows "Active" indicator (green dot)

### 7.2 Auto-Generate Episodes from Schedule
- [ ] After schedule save, verify episodes were auto-generated for next 4 weeks
- [ ] Air datetimes match selected days and time
- [ ] Timezone conversion correct (e.g., UTC+5 schedule → stored as UTC in DB)

### 7.3 Pause Schedule
- [ ] Click "Pause" on active schedule → `is_active` = false
- [ ] Schedule shows inactive indicator

### 7.4 Delete Schedule
- [ ] Click "Delete" on schedule → removed from history

### 7.5 Multiple Patterns
- [ ] Test "Bi-Weekly" → episodes every 2 weeks
- [ ] Test "Movie (One-Time)" → single episode created
- [ ] Test "Twice Per Week" → 2 episodes per week

---

## PHASE 8 — Notification Settings Tests

### 8.1 SMTP Settings
```
URL: http://localhost:8000/profile (or dedicated settings page)
```
- [ ] SMTP form loads with fields: Host, Port, Username, Password, Encryption, From Address, From Name
- [ ] Submit with valid Gmail SMTP values → saved to DB
- [ ] Password stored encrypted in DB (verify raw value is ciphertext, not plaintext)
- [ ] "Test SMTP" button → sends a test email to logged-in user's email
- [ ] Test success → green success message
- [ ] Test failure (wrong credentials) → red error message with reason
- [ ] `tested_at` timestamp updated after successful test

### 8.2 SMS Settings
- [ ] SMS form loads: Gateway URL, API Key, Sender ID, Extra Params (JSON)
- [ ] Submit → API key stored encrypted
- [ ] "Test SMS" button → sends test SMS to user's phone
- [ ] Extra params JSON validated (invalid JSON → error message)

### 8.3 Notification Toggles
- [ ] Toggle "Email Notifications" on/off → `email_notifications` column updates
- [ ] Toggle "SMS Notifications" on/off → `sms_notifications` column updates

---

## PHASE 9 — Reminder Engine & Notification Dispatch Tests

### 9.1 Manual Command Test
```bash
# Create an episode airing in 10 minutes
php artisan tinker
# Find a show and set episode air_datetime to 10 mins from now
$ep = App\Models\Episode::first();
$ep->air_datetime = now()->addMinutes(10);
$ep->is_aired = false;
$ep->notified = false;
$ep->save();

# Run the check command
php artisan app:check-upcoming-episodes
```
- [ ] Command output shows "Dispatched 1 episode reminder."
- [ ] `notified` flag on episode = true after command runs
- [ ] Job appears in `jobs` table (DB queue)

### 9.2 Queue Worker Processes Job
```bash
php artisan queue:work --once
```
- [ ] Job processed without errors
- [ ] Email received in inbox (if SMTP configured)
- [ ] Email has correct content: show title, episode number, air time
- [ ] `NotificationLog` record created in DB with status=`sent`

### 9.3 Failed Job Handling
- [ ] Set invalid SMTP credentials → job fails
- [ ] `NotificationLog` record created with status=`failed` and error_message
- [ ] Failed job moves to `failed_jobs` table

### 9.4 SMS Dispatch
- [ ] Enable SMS for user with valid SMS gateway
- [ ] Run command → SMS job dispatched
- [ ] SMS received on configured phone number

### 9.5 Duplicate Prevention
- [ ] Run `app:check-upcoming-episodes` twice for same episode
- [ ] Second run: episode already `notified=true` → 0 jobs dispatched

---

## PHASE 10 — Upcoming Episodes Page Tests

```
URL: http://localhost:8000/upcoming
```
- [ ] Page loads with list of upcoming (unaired) episodes across all user's shows
- [ ] Episodes sorted by `air_datetime` ascending
- [ ] Each entry shows: show title, episode number, air date/time in user's timezone
- [ ] "Mark Watched" action available inline
- [ ] Empty state if no upcoming episodes

---

## PHASE 11 — Admin Console Tests

```
# First: set ADMIN_EMAIL in .env to match your registered user's email
URL: http://localhost:8000/admin/notifications
```

### 11.1 Access Control
- [ ] Non-admin user visiting `/admin/notifications` → 403 Forbidden
- [ ] Admin user visiting `/admin/notifications` → page loads
- [ ] "Admin Console" link in sidebar only visible to admin email

### 11.2 Notification Log Table
- [ ] Table shows all notification_logs records
- [ ] Columns: ID, User, Channel (email/sms icon), Show/Episode, Status (badge), Message, Time
- [ ] Status badges: sent=green, failed=red, pending=amber
- [ ] Show title is a clickable link to watchlist.show
- [ ] Episode shows correct Season/Episode numbers (S1 E3 format)
- [ ] Empty state if no logs ("No notification logs found.")
- [ ] Pagination at 50 records per page

---

## PHASE 12 — JSON Import Tool Tests

```
URL: http://localhost:8000/tools/import
```
- [ ] Import page loads with file upload / JSON paste area
- [ ] Submit valid MAL-format JSON → shows imported to watchlist
- [ ] Submit malformed JSON → error message shown (not a crash)
- [ ] Submit JSON with missing required fields → per-record error feedback
- [ ] Duplicate titles handled (slug uniqueness check)

**Test JSON Format:**
```json
[
  {
    "title": "Attack on Titan",
    "type": "anime",
    "status": "watching",
    "total_episodes": 75,
    "jikan_id": 16498
  }
]
```
- [ ] Above JSON imports "Attack on Titan" to watchlist with correct fields

---

## PHASE 13 — UI Quality & Cross-Browser Tests

### 13.1 Dark Mode
- [ ] Toggle dark mode → all surfaces change (sidebar, cards, inputs, badges)
- [ ] No elements remain white/light in dark mode
- [ ] Dark mode persists after page reload
- [ ] Dark mode persists across different pages (dashboard → watchlist → profile)

### 13.2 Responsive Design (Mobile — 375px width)
- [ ] Landing page: hero text readable, CTA buttons accessible
- [ ] Dashboard: stat cards stack vertically
- [ ] Watchlist: grid shows 2 columns
- [ ] Show detail: tabs accessible, poster full width
- [ ] Sidebar: hidden by default, hamburger button in topbar opens it
- [ ] Mobile overlay closes sidebar when tapped outside

### 13.3 Responsive Design (Tablet — 768px)
- [ ] Sidebar visible but collapsible
- [ ] Grid shows 3 columns

### 13.4 Interactive Elements
- [ ] All buttons have hover states
- [ ] All links show cursor pointer
- [ ] Form inputs: focus ring visible (blue glow)
- [ ] Cards: hover lift effect (-translate-y-1) visible
- [ ] Poster cards: hover overlay + action buttons appear
- [ ] All animations smooth (no jank/flicker)

### 13.5 Accessibility
- [ ] All images have `alt` attributes
- [ ] Form labels associated with inputs (via `for`/`id`)
- [ ] Buttons without text labels have `title` attributes
- [ ] Tab navigation works through form elements

---

## PHASE 14 — End-to-End User Journey Test

**Full user lifecycle from registration to receiving a notification:**

1. [ ] Register new account
2. [ ] Complete profile: set timezone to `Asia/Karachi`, add phone number
3. [ ] Configure SMTP settings → run test → success
4. [ ] Add anime "Demon Slayer" via Jikan search → saves with poster
5. [ ] Go to show detail → Episodes tab → bulk add 5 episodes
6. [ ] Set episode 1 air_datetime to 5 minutes from now
7. [ ] Go to Schedule tab → create weekly schedule
8. [ ] Go to Dashboard → "Airing Today" panel shows the episode
9. [ ] Run `php artisan app:check-upcoming-episodes`
10. [ ] Run `php artisan queue:work --once`
11. [ ] Check email inbox → reminder received
12. [ ] Go to Admin Console → notification log shows sent record
13. [ ] Mark episode 1 as watched
14. [ ] Check progress bar on show overview → updated to 20%
15. [ ] Logout → login again → all data persists

---

## PHASE 15 — Performance & Regression Tests

### 15.1 Large Dataset
```bash
# Seed 100 shows, 1000 episodes
php artisan db:seed --class=LargeDataSeeder
```
- [ ] Watchlist grid loads in < 3 seconds
- [ ] Filtering doesn't cause timeout
- [ ] Dashboard stats query runs in < 1 second

### 15.2 API Rate Limiting
- [ ] Search TMDB 10 times in rapid succession → no 429 errors (caching prevents repeated calls)
- [ ] Jikan 10 rapid searches → caching prevents API hammering

### 15.3 Queue Stress Test
- [ ] Dispatch 50 reminder jobs
- [ ] `php artisan queue:work --timeout=60`
- [ ] All 50 jobs complete, no memory leaks

---

## Bug Fix Verification Checklist

After each code fix, re-run the relevant phase test:

| Fix | Phase to Re-test |
|-----|-----------------|
| `buildMailer()` added to `UserSmtpSetting` | Phase 9, 8.1 |
| `api_url` → `gateway_url` in job | Phase 9 |
| `extra_parameters` → `extra_params` in job | Phase 9 |
| `NotificationLog::create()` added to job | Phase 9.2, 11.2 |
| Email blade template created | Phase 9.2 |
| Admin view field names fixed | Phase 11.2 |
| `upcoming/index.blade.php` created | Phase 10 |
| `withCount('episodes')` in WatchlistGrid | Phase 5.1 |
| Sidebar dead links fixed | All phases |
| Pagination custom view | Phase 5.4, 11.2 |
| Scheduler interval fixed | Phase 9 |
| SMTP/SMS test routes added | Phase 8.1, 8.2 |

---

## Automated Browser Test Script

> Run after `php artisan serve --port=8000` is active

```javascript
// Selenium/Playwright pseudo-code for Phase 1-3 automation
// File: tests/browser/auth_flow.js

const tests = [
  // Landing page
  { url: '/', expect: ['WatchList Reminder', 'Start Tracking Free', 'Track Every Show'] },
  
  // Registration
  { 
    url: '/register', 
    fill: { name: 'Test User', email: 'test@test.com', password: 'password123', 'password_confirmation': 'password123' },
    submit: true,
    expectRedirect: '/dashboard'
  },
  
  // Dashboard
  {
    url: '/dashboard',
    expect: ['Welcome back', 'Total Shows', 'Watching', 'Airing Today', 'This Week']
  },
  
  // Add show
  {
    url: '/watchlist/add',
    tab: 'manual',
    fill: { title: 'Test Show', type: 'drama', status: 'watching' },
    submit: true,
    expectRedirect: '/watchlist/'
  }
];
```

**Run automated browser tests with this command prompt:**
```bash
# Install Laravel Dusk for browser automation
composer require laravel/dusk --dev
php artisan dusk:install
php artisan dusk
```

---

## Test Results Tracking

| Phase | Date | Tester | Status | Notes |
|-------|------|--------|--------|-------|
| Phase 1 | | | ⏳ Pending | |
| Phase 2 | | | ⏳ Pending | |
| Phase 3 | | | ⏳ Pending | |
| Phase 4 | | | ⏳ Pending | |
| Phase 5 | | | ⏳ Pending | |
| Phase 6 | | | ⏳ Pending | |
| Phase 7 | | | ⏳ Pending | |
| Phase 8 | | | ⏳ Pending | |
| Phase 9 | | | ⏳ Pending | |
| Phase 10 | | | ⏳ Pending | |
| Phase 11 | | | ⏳ Pending | |
| Phase 12 | | | ⏳ Pending | |
| Phase 13 | | | ⏳ Pending | |
| Phase 14 | | | ⏳ Pending | |
| Phase 15 | | | ⏳ Pending | |
