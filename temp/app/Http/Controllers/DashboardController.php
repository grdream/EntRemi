<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\Episode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $userTimezone = auth()->user()->timezone ?? 'UTC';

        // Base query for user's shows
        $userShowsQuery = Show::where('user_id', $userId);

        // Stats
        $totalShows = $userShowsQuery->count();
        $watchingCount = Show::where('user_id', $userId)->where('status', 'watching')->count();

        // Calculate today and week boundaries in user's timezone
        // Then convert to UTC to query the database since DB stores UTC
        $startOfTodayUTC = Carbon::now($userTimezone)->startOfDay()->utc();
        $endOfTodayUTC = Carbon::now($userTimezone)->endOfDay()->utc();

        $startOfWeekUTC = Carbon::now($userTimezone)->startOfWeek()->utc();
        $endOfWeekUTC = Carbon::now($userTimezone)->endOfWeek()->utc();

        $airingTodayCount = Episode::whereHas('show', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('is_aired', false)
            ->whereBetween('air_datetime', [$startOfTodayUTC, $endOfTodayUTC])
            ->count();

        $thisWeekCount = Episode::whereHas('show', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('is_aired', false)
            ->whereBetween('air_datetime', [$startOfTodayUTC, $endOfWeekUTC]) // From today until end of week
            ->count();

        return view('dashboard', compact(
            'totalShows', 
            'watchingCount', 
            'airingTodayCount', 
            'thisWeekCount'
        ));
    }
}
