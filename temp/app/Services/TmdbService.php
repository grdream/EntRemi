<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TmdbService
{
    private string $apiKey;
    private string $baseUrl;
    private string $imageBaseUrl;

    public function __construct()
    {
        $this->apiKey      = config('services.tmdb.api_key', '');
        $this->baseUrl     = config('services.tmdb.base_url', 'https://api.themoviedb.org/3');
        $this->imageBaseUrl= config('services.tmdb.image_base_url', 'https://image.tmdb.org/t/p/w500');
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Search across movies, TV shows (multi-search).
     *
     * @return array{results: array, total_results: int}
     */
    public function search(string $query, int $page = 1): array
    {
        if (!$this->isConfigured() || empty(trim($query))) {
            return ['results' => [], 'total_results' => 0];
        }

        try {
            $response = Http::timeout(8)->get("{$this->baseUrl}/search/multi", [
                'api_key'       => $this->apiKey,
                'query'         => $query,
                'page'          => $page,
                'include_adult' => false,
            ]);

            if ($response->failed()) {
                Log::warning('[TMDB] Search failed', ['status' => $response->status(), 'query' => $query]);
                return ['results' => [], 'total_results' => 0];
            }

            $data    = $response->json();
            $results = collect($data['results'] ?? [])
                ->filter(fn($r) => in_array($r['media_type'] ?? '', ['movie', 'tv']))
                ->map(fn($r)  => $this->normalizeResult($r))
                ->values()
                ->all();

            return [
                'results'       => $results,
                'total_results' => $data['total_results'] ?? count($results),
            ];
        } catch (\Exception $e) {
            Log::error('[TMDB] Search exception: ' . $e->getMessage());
            return ['results' => [], 'total_results' => 0];
        }
    }

    /**
     * Fetch full details for a movie.
     */
    public function getMovie(int|string $id): ?array
    {
        return $this->fetchDetail("movie/{$id}", 'movie');
    }

    /**
     * Fetch full details for a TV show.
     */
    public function getTv(int|string $id): ?array
    {
        return $this->fetchDetail("tv/{$id}", 'tv');
    }

    private function fetchDetail(string $endpoint, string $type): ?array
    {
        if (!$this->isConfigured()) return null;

        try {
            $response = Http::timeout(8)->get("{$this->baseUrl}/{$endpoint}", [
                'api_key' => $this->apiKey,
            ]);

            if ($response->failed()) return null;

            return $this->normalizeResult(array_merge($response->json(), ['media_type' => $type]));
        } catch (\Exception $e) {
            Log::error("[TMDB] fetchDetail({$endpoint}) exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch trending TV shows.
     */
    public function getTrendingShows(): array
    {
        if (!$this->isConfigured()) return [];

        try {
            $response = Http::timeout(8)->get("{$this->baseUrl}/trending/tv/week", [
                'api_key' => $this->apiKey,
            ]);

            if ($response->failed()) return [];

            $data = $response->json();
            return collect($data['results'] ?? [])
                ->take(6)
                ->map(fn($r) => $this->normalizeResult(array_merge($r, ['media_type' => 'tv'])))
                ->values()
                ->all();
        } catch (\Exception $e) {
            Log::error("[TMDB] getTrendingShows exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Normalize a TMDB result into a unified structure for our app.
     */
    public function normalizeResult(array $r): array
    {
        $isMovie   = ($r['media_type'] ?? '') === 'movie';
        $title     = $isMovie ? ($r['title'] ?? '') : ($r['name'] ?? '');
        $releaseDate = $isMovie ? ($r['release_date'] ?? '') : ($r['first_air_date'] ?? '');
        $year      = $releaseDate ? substr($releaseDate, 0, 4) : null;

        return [
            'source'          => 'tmdb',
            'tmdb_id'         => (string) ($r['id'] ?? ''),
            'type'            => $isMovie ? 'movie' : 'tv_series',
            'title'           => $title,
            'description'     => $r['overview'] ?? null,
            'poster_url'      => $r['poster_path'] ? $this->imageBaseUrl . $r['poster_path'] : null,
            'backdrop_url'    => $r['backdrop_path'] ? "https://image.tmdb.org/t/p/w1280" . $r['backdrop_path'] : null,
            'year'            => $year,
            'rating'          => isset($r['vote_average']) ? number_format($r['vote_average'], 1) : null,
            'total_episodes'  => $r['number_of_episodes'] ?? null,
            'genres'          => collect($r['genres'] ?? [])->pluck('name')->all(),
            'language'        => $r['original_language'] ?? null,
            'country'         => isset($r['origin_country']) ? implode(', ', (array)$r['origin_country']) : null,
            'imdb_id'         => $r['imdb_id'] ?? null,
        ];
    }
}
