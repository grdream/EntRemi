<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\Episode;
use Carbon\Carbon;

class ScheduleEngine
{
    /**
     * Automatically generates episodes based on the show's schedule pattern.
     */
    public function generateEpisodes(Schedule $schedule, int $limit = 24): int
    {
        if ($schedule->pattern === 'irregular') {
            return 0; // Irregular means manual episode adding
        }

        $show = $schedule->show;
        
        // Determine how many episodes we should generate
        // If the show has a defined total_episodes, generate up to that.
        // Otherwise, generate up to $limit slots ahead to avoid infinite generation.
        $totalWanted = $show->total_episodes ?: ($limit * max(1, $schedule->episodes_per_slot));
        
        // Find current max episode number
        $currentEpNo = Episode::where('show_id', $show->id)->max('episode_no') ?? 0;
        
        if ($currentEpNo >= $totalWanted) {
            return 0; // Already generated enough
        }

        $episodesToGenerate = $totalWanted - $currentEpNo;
        $slotsRequired = (int) ceil($episodesToGenerate / max(1, $schedule->episodes_per_slot));

        $tz = $schedule->timezone;
        $airTime = $schedule->air_time; // Format H:i
        
        // Start from the user's defined start_date
        $baseDate = Carbon::parse($schedule->start_date, $tz)->setTimeFromTimeString($airTime);
        
        // If episodes already exist, we need to find the last generated date 
        // to continue from there. But for simplicity, if this is a fresh schedule,
        // we generate from start_date. If they change the schedule, previous schedules
        // are deactivated, and we generate from the new start_date.
        
        $dates = $this->calculateDates($schedule, $baseDate, $slotsRequired);
        
        $insertedCount = 0;

        foreach ($dates as $date) {
            for ($i = 0; $i < $schedule->episodes_per_slot; $i++) {
                if ($currentEpNo >= $totalWanted) {
                    break 2;
                }
                $currentEpNo++;
                
                Episode::create([
                    'show_id'      => $show->id,
                    'episode_no'   => $currentEpNo,
                    'season_no'    => null,
                    'title'        => 'Episode ' . $currentEpNo,
                    'air_datetime' => $date->copy()->utc()->toDateTimeString(),
                    'is_aired'     => $date->copy()->utc()->isPast(),
                    'notified'     => false,
                ]);
                $insertedCount++;
            }
        }

        return $insertedCount;
    }

    protected function calculateDates(Schedule $schedule, Carbon $start, int $count): array
    {
        $dates = [];
        $current = $start->copy();
        $days = array_map('strtolower', $schedule->days_of_week ?? []);

        if ($schedule->pattern === 'movie_one_time') {
            return [$current];
        }

        $lastWeekNo = $current->isoWeek();

        for ($i = 0; $i < $count;) {
            if ($schedule->end_date && $current->copy()->startOfDay()->isAfter(Carbon::parse($schedule->end_date, $schedule->timezone)->endOfDay())) {
                break; // We've passed the strict end date
            }

            $isValid = false;
            $dayName = strtolower($current->englishDayOfWeek);
            
            if ($schedule->pattern === 'daily') {
                $isValid = true;
            } elseif ($schedule->pattern === 'monthly') {
                $isValid = true; // Handled below by adding a month directly
            } elseif (in_array($schedule->pattern, ['weekly', 'bi_weekly', 'twice_per_week'])) {
                if (empty($days) || in_array($dayName, $days)) {
                    $isValid = true;
                }
            }

            if ($isValid) {
                $dates[] = $current->copy();
                $i++;
                
                if ($schedule->pattern === 'monthly') {
                    $current->addMonth();
                    continue;
                }
                
                // If it's weekly but no days are specified, default to adding 7 days
                if (empty($days) && in_array($schedule->pattern, ['weekly', 'bi_weekly'])) {
                    $current->addDays($schedule->pattern === 'bi_weekly' ? 14 : 7);
                    continue;
                }
            }

            $current->addDay();
            
            // Check for week rollover to handle bi-weekly skips
            if ($schedule->pattern === 'bi_weekly' && !empty($days)) {
                if ($current->isoWeek() !== $lastWeekNo) {
                    $current->addWeeks(1); // Skip the entire next week
                    $lastWeekNo = $current->isoWeek();
                }
            } else {
                $lastWeekNo = $current->isoWeek();
            }
            
            // Safety break
            if ($current->diffInYears($start) > 5) {
                break;
            }
        }

        return $dates;
    }
}
