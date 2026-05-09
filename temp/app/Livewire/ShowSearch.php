<?php

namespace App\Livewire;

use App\Services\TmdbService;
use App\Services\JikanService;
use Livewire\Component;
use Livewire\Attributes\Url;

class ShowSearch extends Component
{
    #[Url(except: '')]
    public string $query = '';

    public string $source = 'all'; // 'all', 'tmdb', 'jikan'
    public array  $results = [];
    public bool   $searching = false;
    public bool   $searched = false;
    public ?array $selected = null;  // the result user clicked to add

    protected TmdbService $tmdb;
    protected JikanService $jikan;

    public function boot(TmdbService $tmdb, JikanService $jikan): void
    {
        $this->tmdb  = $tmdb;
        $this->jikan = $jikan;
    }

    /**
     * Triggered by live wire:model.live.debounce.600ms on query input.
     */
    public function updatedQuery(): void
    {
        if (strlen(trim($this->query)) >= 2) {
            $this->search();
        } else {
            $this->results  = [];
            $this->searched = false;
        }
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

        $q = trim($this->query);

        $tmdbResults  = [];
        $jikanResults = [];

        if (in_array($this->source, ['all', 'tmdb'])) {
            $tmdbResults = $this->tmdb->search($q)['results'];
        }

        if (in_array($this->source, ['all', 'jikan'])) {
            $jikanResults = $this->jikan->search($q)['results'];
        }

        // Interleave: tmdb first, then jikan; max 20
        $this->results  = array_slice(array_merge($tmdbResults, $jikanResults), 0, 20);
        $this->searched = true;
        $this->searching = false;
    }

    public function selectResult(int $index): void
    {
        $this->selected = $this->results[$index] ?? null;
    }

    public function clearSelection(): void
    {
        $this->selected = null;
    }

    public function clearSearch(): void
    {
        $this->query    = '';
        $this->results  = [];
        $this->searched = false;
        $this->selected = null;
    }

    public function render()
    {
        return view('livewire.show-search');
    }
}
