<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JikanService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.jikan.base_url', 'https://api.jikan.moe/v4');
    }

    /**
     * Search for anime by title.
     *
     * @return array{results: array, total_results: int}
     */
    public function search(string $query, int $page = 1): array
    {
        if (empty(trim($query))) {
            return ['results' => [], 'total_results' => 0];
        }

        try {
            // Jikan rate-limit is 3 req/s — add small delay if needed in production
            $response = Http::timeout(10)->get("{$this->baseUrl}/anime", [
                'q'       => $query,
                'page'    => $page,
                'limit'   => 10,
                'sfw'     => true,
            ]);

            if ($response->failed()) {
                Log::warning('[Jikan] Search failed', ['status' => $response->status(), 'query' => $query]);
                return ['results' => [], 'total_results' => 0];
            }

            $data    = $response->json();
            $results = collect($data['data'] ?? [])
                ->map(fn($r) => $this->normalizeResult($r))
                ->values()
                ->all();

            return [
                'results'       => $results,
                'total_results' => $data['pagination']['items']['total'] ?? count($results),
            ];
        } catch (\Exception $e) {
            Log::error('[Jikan] Search exception: ' . $e->getMessage());
            return ['results' => [], 'total_results' => 0];
        }
    }

    /**
     * Fetch full anime detail by Jikan/MAL ID.
     */
    public function getAnime(int|string $id): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/anime/{$id}");
            if ($response->failed()) return null;
            $data = $response->json();
            return $this->normalizeResult($data['data'] ?? []);
        } catch (\Exception $e) {
            Log::error("[Jikan] getAnime({$id}) exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Normalize a Jikan result into unified structure.
     */
    public function normalizeResult(array $r): array
    {
        $episodes = $r['episodes'] ?? null;
        $type     = ($r['type'] ?? '') === 'Movie' ? 'anime_movie' : 'anime';

        return [
            'source'         => 'jikan',
            'jikan_id'       => (string) ($r['mal_id'] ?? ''),
            'type'           => $type,
            'title'          => $r['title_english'] ?? $r['title'] ?? '',
            'description'    => $r['synopsis'] ?? null,
            'poster_url'     => $r['images']['jpg']['large_image_url'] ?? $r['images']['jpg']['image_url'] ?? null,
            'backdrop_url'   => null,
            'year'           => $r['year'] ?? ($r['aired']['prop']['from']['year'] ?? null),
            'rating'         => isset($r['score']) ? number_format($r['score'], 1) : null,
            'total_episodes' => $episodes,
            'genres'         => collect($r['genres'] ?? [])->pluck('name')->all(),
            'language'       => 'Japanese',
            'country'        => 'Japan',
            'status'         => $this->mapStatus($r['status'] ?? ''),
        ];
    }

    private function mapStatus(string $status): string
    {
        return match (strtolower($status)) {
            'currently airing' => 'watching',
            'finished airing'  => 'completed',
            'not yet aired'    => 'plan_to_watch',
            default            => 'plan_to_watch',
        };
    }
}
