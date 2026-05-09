<?php

namespace App\Livewire;

use App\Models\Episode;
use App\Models\Show;
use Livewire\Component;
use Livewire\Attributes\On;

class EpisodeManager extends Component
{
    // The show being managed
    public int    $showId;
    public string $showSlug;
    public string $userTimezone;

    // Filter / sort state
    public string $filterStatus = 'all';   // all | aired | upcoming
    public string $sortBy       = 'episode_no'; // episode_no | air_datetime
    public string $search       = '';

    // Add single episode form
    public bool   $showAddForm   = false;
    public int    $newEpisodeNo  = 1;
    public ?int   $newSeasonNo   = null;
    public string $newTitle      = '';
    public string $newAirDate    = '';
    public string $newAirTime    = '20:00';
    public ?int   $newDuration   = null;
    public bool   $newIsAired    = false;

    // Bulk add form
    public bool   $showBulkForm  = false;
    public int    $bulkFrom      = 1;
    public int    $bulkTo        = 12;
    public ?int   $bulkSeason    = null;
    public string $bulkFirstDate = '';
    public string $bulkAirTime   = '20:00';
    public int    $bulkInterval  = 7;

    // Editing state
    public ?int   $editingId     = null;
    public array  $editData      = [];

    public function mount(Show $show, string $timezone = 'UTC'): void
    {
        $this->showId       = $show->id;
        $this->showSlug     = $show->slug;
        $this->userTimezone = $timezone;
        // Pre-fill next episode number
        $this->newEpisodeNo = ($show->episodes()->max('episode_no') ?? 0) + 1;
        $this->newAirDate   = now()->format('Y-m-d');
    }

    // ─── Computed episodes list ────────────────────────────────────────────────

    public function getEpisodesProperty(): \Illuminate\Database\Eloquent\Collection
    {
        $query = Episode::where('show_id', $this->showId);

        if ($this->filterStatus === 'aired') {
            $query->where('is_aired', true);
        } elseif ($this->filterStatus === 'upcoming') {
            $query->where('is_aired', false);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('episode_no', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy($this->sortBy);

        return $query->get();
    }

    // ─── Add single episode ────────────────────────────────────────────────────

    public function addEpisode(): void
    {
        $this->validate([
            'newEpisodeNo' => 'required|integer|min:1',
            'newSeasonNo'  => 'nullable|integer|min:1',
            'newTitle'     => 'nullable|string|max:255',
            'newAirDate'   => 'nullable|date',
            'newAirTime'   => 'nullable|date_format:H:i',
            'newDuration'  => 'nullable|integer|min:1',
        ]);

        $airDatetime = null;
        if ($this->newAirDate) {
            $airDatetime = \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i',
                $this->newAirDate . ' ' . ($this->newAirTime ?: '00:00'),
                $this->userTimezone
            )->utc()->toDateTimeString();
        }

        Episode::create([
            'show_id'          => $this->showId,
            'episode_no'       => $this->newEpisodeNo,
            'season_no'        => $this->newSeasonNo,
            'title'            => $this->newTitle ?: null,
            'air_datetime'     => $airDatetime,
            'duration_minutes' => $this->newDuration,
            'is_aired'         => $this->newIsAired,
            'notified'         => false,
        ]);

        // Reset form
        $this->newEpisodeNo = Episode::where('show_id', $this->showId)->max('episode_no') + 1;
        $this->newTitle     = '';
        $this->newAirDate   = now()->format('Y-m-d');
        $this->showAddForm  = false;

        session()->flash('success', 'Episode added.');
    }

    // ─── Bulk add ─────────────────────────────────────────────────────────────

    public function bulkAdd(): void
    {
        $this->validate([
            'bulkFrom'      => 'required|integer|min:1',
            'bulkTo'        => 'required|integer|gte:bulkFrom',
            'bulkSeason'    => 'nullable|integer|min:1',
            'bulkFirstDate' => 'nullable|date',
            'bulkAirTime'   => 'nullable|date_format:H:i',
            'bulkInterval'  => 'required|integer|min:1|max:365',
        ]);

        $baseDate = null;
        if ($this->bulkFirstDate) {
            $baseDate = \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i',
                $this->bulkFirstDate . ' ' . ($this->bulkAirTime ?: '00:00'),
                $this->userTimezone
            )->utc();
        }

        $inserted = 0;
        for ($ep = $this->bulkFrom; $ep <= $this->bulkTo; $ep++) {
            $exists = Episode::where('show_id', $this->showId)
                ->where('season_no', $this->bulkSeason)
                ->where('episode_no', $ep)
                ->exists();

            if ($exists) continue;

            $airDatetime = $baseDate
                ? $baseDate->copy()->addDays(($ep - $this->bulkFrom) * $this->bulkInterval)->toDateTimeString()
                : null;

            Episode::create([
                'show_id'      => $this->showId,
                'season_no'    => $this->bulkSeason,
                'episode_no'   => $ep,
                'air_datetime' => $airDatetime,
                'is_aired'     => false,
                'notified'     => false,
            ]);
            $inserted++;
        }

        $this->showBulkForm = false;
        session()->flash('success', "{$inserted} episodes added.");
    }

    // ─── Toggle aired ──────────────────────────────────────────────────────────

    public function toggleAired(int $episodeId): void
    {
        $episode = Episode::where('id', $episodeId)->where('show_id', $this->showId)->firstOrFail();
        $episode->is_aired = !$episode->is_aired;
        $episode->save();
    }

    // ─── Inline edit ──────────────────────────────────────────────────────────

    public function startEdit(int $episodeId): void
    {
        $ep = Episode::findOrFail($episodeId);
        $this->editingId = $episodeId;
        $this->editData  = [
            'episode_no'       => $ep->episode_no,
            'season_no'        => $ep->season_no,
            'title'            => $ep->title ?? '',
            'air_date'         => $ep->air_datetime ? $ep->air_datetime->setTimezone($this->userTimezone)->format('Y-m-d') : '',
            'air_time'         => $ep->air_datetime ? $ep->air_datetime->setTimezone($this->userTimezone)->format('H:i') : '20:00',
            'duration_minutes' => $ep->duration_minutes,
            'is_aired'         => $ep->is_aired,
        ];
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->editData  = [];
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editData.episode_no'   => 'required|integer|min:1',
            'editData.season_no'    => 'nullable|integer|min:1',
            'editData.title'        => 'nullable|string|max:255',
            'editData.air_date'     => 'nullable|date',
            'editData.air_time'     => 'nullable|date_format:H:i',
            'editData.duration_minutes' => 'nullable|integer|min:1',
        ]);

        $episode = Episode::where('id', $this->editingId)->where('show_id', $this->showId)->firstOrFail();

        $airDatetime = null;
        if (!empty($this->editData['air_date'])) {
            $airDatetime = \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i',
                $this->editData['air_date'] . ' ' . ($this->editData['air_time'] ?: '00:00'),
                $this->userTimezone
            )->utc()->toDateTimeString();
        }

        $episode->update([
            'episode_no'       => $this->editData['episode_no'],
            'season_no'        => $this->editData['season_no'] ?: null,
            'title'            => $this->editData['title'] ?: null,
            'air_datetime'     => $airDatetime,
            'duration_minutes' => $this->editData['duration_minutes'] ?: null,
            'is_aired'         => $this->editData['is_aired'] ?? false,
        ]);

        $this->cancelEdit();
        session()->flash('success', 'Episode updated.');
    }

    // ─── Delete ───────────────────────────────────────────────────────────────

    public function deleteEpisode(int $episodeId): void
    {
        Episode::where('id', $episodeId)->where('show_id', $this->showId)->delete();
        session()->flash('success', 'Episode deleted.');
    }

    public function render()
    {
        return view('livewire.episode-manager', [
            'episodes' => $this->episodes,
        ]);
    }
}
