<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Episode;
use Carbon\Carbon;

class UpcomingEpisodes extends Component
{
    public $timeframe = 'today'; // 'today' or 'week'

    public function toggleWatched($episodeId)
    {
        $episode = Episode::where('id', $episodeId)
            ->whereHas('show', function($q) {
                $q->where('user_id', auth()->id());
            })->first();

        if ($episode) {
            $episode->is_aired = !$episode->is_aired;
            $episode->save();
        }
    }

    public function render()
    {
        $userId = auth()->id();
        $userTimezone = auth()->user()->timezone ?? 'UTC';

        $start = Carbon::now($userTimezone)->startOfDay()->utc();
        
        if ($this->timeframe === 'today') {
            $end = Carbon::now($userTimezone)->endOfDay()->utc();
        } else {
            $end = Carbon::now($userTimezone)->endOfWeek()->utc();
        }

        $episodes = Episode::with('show')
            ->whereHas('show', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('is_aired', false)
            ->whereBetween('air_datetime', [$start, $end])
            ->orderBy('air_datetime', 'asc')
            ->get();

        return view('livewire.upcoming-episodes', [
            'episodes' => $episodes,
            'userTimezone' => $userTimezone
        ]);
    }
}
