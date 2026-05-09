<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Show;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EpisodeController extends Controller
{
    /**
     * Store a single episode for a show.
     */
    public function store(Request $request, string $slug): RedirectResponse
    {
        $show = Show::where('slug', $slug)->where('user_id', auth()->id())->firstOrFail();

        $data = $request->validate([
            'episode_no'       => ['required', 'integer', 'min:1'],
            'season_no'        => ['nullable', 'integer', 'min:1'],
            'title'            => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'air_datetime'     => ['nullable', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'is_aired'         => ['boolean'],
        ]);

        $data['show_id'] = $show->id;
        $data['is_aired'] = $request->boolean('is_aired');

        Episode::create($data);

        return back()->with('success', 'Episode added.');
    }

    /**
     * Bulk create episodes in a range.
     * POST /watchlist/{slug}/episodes/bulk
     */
    public function bulkStore(Request $request, string $slug): RedirectResponse
    {
        $show = Show::where('slug', $slug)->where('user_id', auth()->id())->firstOrFail();

        $data = $request->validate([
            'from_episode'    => ['required', 'integer', 'min:1'],
            'to_episode'      => ['required', 'integer', 'gte:from_episode'],
            'season_no'       => ['nullable', 'integer', 'min:1'],
            'first_air_date'  => ['nullable', 'date'],
            'interval_days'   => ['nullable', 'integer', 'min:1', 'max:365'],
            'air_time'        => ['nullable', 'date_format:H:i'],
        ]);

        $from         = (int) $data['from_episode'];
        $to           = (int) $data['to_episode'];
        $seasonNo     = $data['season_no'] ?? null;
        $intervalDays = (int) ($data['interval_days'] ?? 7);
        $airTime      = $data['air_time'] ?? '00:00';

        // Build base datetime from first air date + time
        $baseDate = null;
        if (!empty($data['first_air_date'])) {
            $baseDate = \Carbon\Carbon::parse($data['first_air_date'] . ' ' . $airTime);
        }

        $episodes = [];
        for ($ep = $from; $ep <= $to; $ep++) {
            // Skip if episode already exists for this show+season+episode_no
            $exists = Episode::where('show_id', $show->id)
                ->where('season_no', $seasonNo)
                ->where('episode_no', $ep)
                ->exists();

            if ($exists) continue;

            $airDatetime = null;
            if ($baseDate) {
                $offset = ($ep - $from) * $intervalDays;
                $airDatetime = $baseDate->copy()->addDays($offset)->toDateTimeString();
            }

            $episodes[] = [
                'show_id'      => $show->id,
                'season_no'    => $seasonNo,
                'episode_no'   => $ep,
                'air_datetime' => $airDatetime,
                'is_aired'     => false,
                'notified'     => false,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        if (!empty($episodes)) {
            Episode::insert($episodes);
        }

        $count = count($episodes);
        return back()->with('success', "{$count} episode(s) added successfully.");
    }

    /**
     * Update a single episode.
     */
    public function update(Request $request, string $slug, Episode $episode): RedirectResponse
    {
        // Ensure episode belongs to this user's show
        abort_if($episode->show->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'episode_no'       => ['required', 'integer', 'min:1'],
            'season_no'        => ['nullable', 'integer', 'min:1'],
            'title'            => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'air_datetime'     => ['nullable', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'is_aired'         => ['boolean'],
        ]);

        $data['is_aired'] = $request->boolean('is_aired');
        $episode->update($data);

        return back()->with('success', 'Episode updated.');
    }

    /**
     * Delete an episode.
     */
    public function destroy(string $slug, Episode $episode): RedirectResponse
    {
        abort_if($episode->show->user_id !== auth()->id(), 403);
        $episode->delete();
        return back()->with('success', 'Episode removed.');
    }

    /**
     * Toggle the is_aired status of an episode (AJAX or redirect).
     */
    public function toggleAired(Request $request, string $slug, Episode $episode): JsonResponse|RedirectResponse
    {
        abort_if($episode->show->user_id !== auth()->id(), 403);

        $episode->is_aired = !$episode->is_aired;
        $episode->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'is_aired' => $episode->is_aired,
                'episode'  => $episode->label(),
            ]);
        }

        return back()->with('success', $episode->is_aired ? 'Marked as aired.' : 'Marked as upcoming.');
    }
}
