<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Show;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\ScheduleEngine;

class ScheduleController extends Controller
{
    /**
     * Store or update (upsert) the schedule for a show.
     * One active schedule per show — update if exists.
     */
    public function upsert(Request $request, string $slug, ScheduleEngine $engine): RedirectResponse
    {
        $show = Show::where('slug', $slug)->where('user_id', auth()->id())->firstOrFail();

        $data = $request->validate([
            'pattern'           => ['required', 'in:daily,weekly,bi_weekly,twice_per_week,monthly,irregular,movie_one_time'],
            'days_of_week'      => ['nullable', 'array'],
            'days_of_week.*'    => ['string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'air_time'          => ['required', 'date_format:H:i'],
            'timezone'          => ['required', 'string', 'timezone:all'],
            'episodes_per_slot' => ['nullable', 'integer', 'min:1', 'max:10'],
            'start_date'        => ['required', 'date'],
            'end_date'          => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active'         => ['boolean'],
        ]);

        $data['show_id']          = $show->id;
        $data['is_active']        = $request->boolean('is_active', true);
        $data['episodes_per_slot']= $data['episodes_per_slot'] ?? 1;

        // Deactivate previous schedules
        $show->schedules()->update(['is_active' => false]);

        // Create new active schedule
        $schedule = Schedule::create($data);

        // Generate upcoming episodes using the engine
        $generatedCount = $engine->generateEpisodes($schedule);

        $msg = 'Schedule saved: ' . $schedule->summaryLabel();
        if ($generatedCount > 0) {
            $msg .= " ({$generatedCount} episodes auto-generated).";
        }

        return back()->with('success', $msg);
    }

    /**
     * Deactivate (pause) the current schedule.
     */
    public function deactivate(string $slug): RedirectResponse
    {
        $show = Show::where('slug', $slug)->where('user_id', auth()->id())->firstOrFail();
        $show->schedules()->active()->update(['is_active' => false]);
        return back()->with('success', 'Schedule paused.');
    }

    /**
     * Delete a specific schedule.
     */
    public function destroy(string $slug, Schedule $schedule): RedirectResponse
    {
        abort_if($schedule->show->user_id !== auth()->id(), 403);
        $schedule->delete();
        return back()->with('success', 'Schedule deleted.');
    }
}
