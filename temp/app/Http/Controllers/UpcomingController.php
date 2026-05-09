<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Show;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UpcomingController extends Controller
{
    public function index(Request $request): View
    {
        $user     = auth()->user();
        $timezone = $user->timezone ?? 'UTC';
        $days     = (int) $request->query('days', 14);
        $type     = $request->query('type');

        // Get all show IDs for this user
        $showIds = Show::forUser($user->id)->pluck('id');

        $query = Episode::with('show')
            ->whereIn('show_id', $showIds)
            ->where('is_aired', false)
            ->whereNotNull('air_datetime')
            ->orderBy('air_datetime');

        // Filter by window
        if ($days > 0) {
            $query->where('air_datetime', '<=', now()->addDays($days));
        }

        // Filter by show type
        if ($type) {
            $query->whereHas('show', fn($q) => $q->where('type', $type));
        }

        $episodes = $query->get();

        // Group by date in user's timezone
        $grouped = $episodes->groupBy(function ($ep) use ($timezone) {
            $local = $ep->air_datetime->setTimezone($timezone);

            if ($local->isToday()) return 'Today';
            if ($local->isTomorrow()) return 'Tomorrow';
            if ($local->diffInDays(now()) < 7) return $local->format('l'); // Monday, Tuesday…
            return $local->format('l, M d');
        });

        return view('upcoming.index', compact('grouped'));
    }
}
