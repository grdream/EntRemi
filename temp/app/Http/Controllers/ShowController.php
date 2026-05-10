<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\Reminder;
use App\Services\TmdbService;
use App\Services\JikanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShowController extends Controller
{
    public function __construct(
        private TmdbService $tmdb,
        private JikanService $jikan,
    ) {}

    /**
     * Display the user's watchlist with filters.
     */
    public function index(Request $request): View
    {
        $user  = auth()->user();
        $query = Show::forUser($user->id)->withCount(['episodes']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }
        if ($search = $request->query('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $sort         = $request->query('sort', 'updated_at');
        $dir          = $request->query('dir', 'desc');
        $allowedSorts = ['title', 'year', 'rating', 'created_at', 'updated_at', 'status'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $dir === 'asc' ? 'asc' : 'desc');
        }

        $shows = $query->paginate(18)->withQueryString();

        $stats = [
            'total'         => Show::forUser($user->id)->count(),
            'watching'      => Show::forUser($user->id)->where('status', 'watching')->count(),
            'completed'     => Show::forUser($user->id)->where('status', 'completed')->count(),
            'plan_to_watch' => Show::forUser($user->id)->where('status', 'plan_to_watch')->count(),
        ];

        return view('watchlist.index', compact('shows', 'stats'));
    }

    /**
     * Show the "Add Show" form.
     */
    public function create(): View
    {
        $trendingAnime = collect($this->jikan->getTopAnime());
        $trendingTv = collect($this->tmdb->getTrendingShows());

        $recommendations = $trendingAnime->merge($trendingTv)->shuffle()->take(6);

        return view('watchlist.create', [
            'tmdbConfigured' => $this->tmdb->isConfigured(),
            'recommendations' => $recommendations,
        ]);
    }

    /**
     * Store a new show.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'type'           => ['required', 'in:drama,movie,anime,tv_series,anime_movie,other'],
            'status'         => ['required', 'in:watching,completed,on_hold,dropped,plan_to_watch'],
            'description'    => ['nullable', 'string'],
            'poster_url'     => ['nullable', 'url'],
            'backdrop_url'   => ['nullable', 'url'],
            'tmdb_id'        => ['nullable', 'string'],
            'jikan_id'       => ['nullable', 'string'],
            'imdb_id'        => ['nullable', 'string'],
            'year'           => ['nullable', 'string', 'max:10'],
            'rating'         => ['nullable', 'string', 'max:10'],
            'total_episodes' => ['nullable', 'integer', 'min:1'],
            'country'        => ['nullable', 'string', 'max:80'],
            'language'       => ['nullable', 'string', 'max:80'],
            'genres'         => ['nullable', 'array'],
            'genres.*'       => ['string'],
        ]);

        $data['user_id'] = auth()->id();
        $data['slug']    = Show::generateUniqueSlug($data['title']);

        $show = Show::create($data);

        return redirect()
            ->route('watchlist.show', $show->slug)
            ->with('success', '"' . $show->title . '" added to your watchlist!');
    }

    /**
     * Display a show's detail page.
     */
    public function show(string $slug): View
    {
        $show = Show::where('slug', $slug)
            ->where('user_id', auth()->id())
            ->with(['notes'])
            ->firstOrFail();

        $upcomingEpisodes = $show->episodes()
            ->where('is_aired', false)
            ->whereNotNull('air_datetime')
            ->orderBy('air_datetime')
            ->limit(5)
            ->get();

        $airedCount     = $show->episodes()->where('is_aired', true)->count();
        $episodeCount   = $show->episodes()->count();
        $activeSchedule = $show->schedules()->where('is_active', true)->latest()->first();
        $allSchedules   = $show->schedules()->orderByDesc('created_at')->get();

        // Show-level reminder for the current user
        $reminder = Reminder::where('user_id', auth()->id())
            ->where('show_id', $show->id)
            ->whereNull('episode_id')
            ->first();

        return view('watchlist.show', compact(
            'show', 'upcomingEpisodes', 'airedCount',
            'episodeCount', 'activeSchedule', 'allSchedules', 'reminder'
        ));
    }

    /**
     * Show the edit form.
     */
    public function edit(string $slug): View
    {
        $show = Show::where('slug', $slug)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('watchlist.edit', compact('show'));
    }

    /**
     * Update an existing show.
     */
    public function update(Request $request, string $slug): RedirectResponse
    {
        $show = Show::where('slug', $slug)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $data = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'type'           => ['required', 'in:drama,movie,anime,tv_series,anime_movie,other'],
            'status'         => ['required', 'in:watching,completed,on_hold,dropped,plan_to_watch'],
            'description'    => ['nullable', 'string'],
            'poster_url'     => ['nullable', 'url'],
            'year'           => ['nullable', 'string', 'max:10'],
            'rating'         => ['nullable', 'string', 'max:10'],
            'total_episodes' => ['nullable', 'integer', 'min:1'],
            'country'        => ['nullable', 'string', 'max:80'],
            'language'       => ['nullable', 'string', 'max:80'],
            'genres'         => ['nullable', 'array'],
            'genres.*'       => ['string'],
        ]);

        if ($show->title !== $data['title']) {
            $data['slug'] = Show::generateUniqueSlug($data['title']);
        }

        $show->update($data);

        return redirect()
            ->route('watchlist.show', $show->fresh()->slug)
            ->with('success', '"' . $show->title . '" updated successfully.');
    }

    /**
     * Soft-delete a show.
     */
    public function destroy(string $slug): RedirectResponse
    {
        $show = Show::where('slug', $slug)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $title = $show->title;
        $show->delete();

        return redirect()
            ->route('watchlist.index')
            ->with('success', '"' . $title . '" removed from your watchlist.');
    }
}
