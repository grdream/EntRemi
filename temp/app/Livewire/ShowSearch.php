<?php

namespace App\Livewire;

use App\Models\Show;
use App\Services\JikanService;
use App\Services\TmdbService;
use App\Services\YoutubeService;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShowSearch extends Component
{
    #[Url(except: '')]
    public string $query = '';

    public string  $source    = 'all';
    public array   $results   = [];
    public bool    $searching = false;
    public bool    $searched  = false;
    public ?array  $selected  = null;
    public bool    $adding    = false;
    public ?string $addStatus = 'plan_to_watch';
    public ?string $addError  = null;
    public bool    $addSuccess = false;

    protected TmdbService   $tmdb;
    protected JikanService  $jikan;
    protected YoutubeService $youtube;

    public function boot(TmdbService $tmdb, JikanService $jikan, YoutubeService $youtube): void
    {
        $this->tmdb    = $tmdb;
        $this->jikan   = $jikan;
        $this->youtube = $youtube;
    }

    public function updatedQuery(): void
    {
        if (strlen(trim($this->query)) >= 2) {
            $this->search();
        } else {
            $this->results  = [];
            $this->searched = false;
            $this->selected = null;
        }
    }

    #[On('search-term-selected')]
    public function handleSearchTermSelected($term): void
    {
        $this->query = $term['term'] ?? $term;
        $this->search();
    }

    public function updatedSource(): void
    {
        if (strlen(trim($this->query)) >= 2) {
            $this->search();
        }
    }

    public function search(): void
    {
        $this->searching = true;
        $this->results   = [];
        $this->selected  = null;
        $this->addSuccess = false;
        $this->addError   = null;

        $q = trim($this->query);

        if ($this->source === 'youtube' || str_starts_with($q, 'http')) {
            $this->results = $this->youtube->search($q)['results'] ?? [];
        } else {
            $tmdbResults  = [];
            $jikanResults = [];
            if (in_array($this->source, ['all', 'tmdb'])) {
                $tmdbResults = $this->tmdb->search($q)['results'] ?? [];
            }
            if (in_array($this->source, ['all', 'jikan'])) {
                $jikanResults = $this->jikan->search($q)['results'] ?? [];
            }
            $this->results = array_slice(array_merge($tmdbResults, $jikanResults), 0, 20);
        }

        $this->searched  = true;
        $this->searching = false;
    }

    /** User clicked a poster card — show detail panel. */
    public function selectResult(int $index): void
    {
        $this->selected   = $this->results[$index] ?? null;
        $this->addSuccess = false;
        $this->addError   = null;
        $this->addStatus  = 'plan_to_watch';
    }

    /** Directly add the selected result to the user's watchlist. */
    public function quickAdd(): void
    {
        if (!$this->selected) return;

        $this->adding   = true;
        $this->addError = null;

        try {
            $data = $this->selected;

            // Resolve type
            $type = $data['type'] ?? 'other';
            $validTypes = ['drama', 'movie', 'anime', 'tv_series', 'anime_movie', 'other'];
            if (!in_array($type, $validTypes)) $type = 'other';

            $show = Show::create([
                'user_id'        => auth()->id(),
                'title'          => $data['title'],
                'slug'           => Show::generateUniqueSlug($data['title']),
                'type'           => $type,
                'status'         => $this->addStatus ?? 'plan_to_watch',
                'description'    => $data['description'] ?? null,
                'poster_url'     => $data['poster_url'] ?? null,
                'backdrop_url'   => $data['backdrop_url'] ?? null,
                'tmdb_id'        => $data['tmdb_id'] ?? null,
                'jikan_id'       => $data['jikan_id'] ?? null,
                'year'           => $data['year'] ?? null,
                'rating'         => $data['rating'] ?? null,
                'total_episodes' => $data['total_episodes'] ?? null,
                'country'        => $data['country'] ?? null,
                'language'       => $data['language'] ?? null,
                'genres'         => $data['genres'] ?? [],
            ]);

            $this->addSuccess = true;
            $this->adding     = false;

            // Redirect to show page after brief success state
            $this->redirectRoute('watchlist.show', ['slug' => $show->slug]);
        } catch (\Throwable $e) {
            $this->addError = 'Failed to add show: ' . $e->getMessage();
            $this->adding   = false;
        }
    }

    public function clearSelection(): void
    {
        $this->selected   = null;
        $this->addError   = null;
        $this->addSuccess = false;
    }

    public function clearSearch(): void
    {
        $this->query      = '';
        $this->results    = [];
        $this->searched   = false;
        $this->selected   = null;
        $this->addSuccess = false;
        $this->addError   = null;
    }

    public function render()
    {
        return view('livewire.show-search');
    }
}
