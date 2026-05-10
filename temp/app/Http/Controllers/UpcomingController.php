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

        // Get all show IDs for this user
        $showIds = Show::forUser($user->id)->pluck('id');

        $baseQuery = Episode::with('show')
            ->whereIn('show_id', $showIds)
            ->where('is_aired', false)
            ->whereNotNull('air_datetime')
            ->orderBy('air_datetime');

        $now       = Carbon::now($timezone);
        $todayEnd  = $now->copy()->endOfDay();
        $weekEnd   = $now->copy()->endOfWeek();
        $monthEnd  = $now->copy()->endOfMonth();

        // Today
        $today = (clone $baseQuery)
            ->whereBetween('air_datetime', [$now->copy()->startOfDay(), $todayEnd])
            ->get();

        // This week (excluding today)
        $week = (clone $baseQuery)
            ->whereBetween('air_datetime', [$now->copy()->startOfDay(), $weekEnd])
            ->get();

        // This month
        $month = (clone $baseQuery)
            ->whereBetween('air_datetime', [$now->copy()->startOfDay(), $monthEnd])
            ->get();

        // All upcoming
        $all = (clone $baseQuery)
            ->where('air_datetime', '>=', $now->copy()->startOfDay())
            ->get();

        return view('upcoming.index', compact('today', 'week', 'month', 'all', 'timezone'));
    }
}
