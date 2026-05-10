<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class YoutubeService
{
    /**
     * Fetch YouTube metadata from oEmbed API based on a URL.
     * Caches the result for 24 hours.
     */
    public function search(string $url): array
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) || !str_contains($url, 'youtube.com') && !str_contains($url, 'youtu.be')) {
            return ['results' => []];
        }

        return Cache::remember('youtube_' . md5($url), now()->addDay(), function () use ($url) {
            try {
                $response = Http::timeout(5)->get('https://www.youtube.com/oembed', [
                    'url'    => $url,
                    'format' => 'json'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    return ['results' => [
                        [
                            'title'          => $data['title'] ?? 'YouTube Video',
                            'type'           => 'other',
                            'source'         => 'youtube',
                            'description'    => 'Author: ' . ($data['author_name'] ?? 'Unknown'),
                            'poster_url'     => $data['thumbnail_url'] ?? null,
                            'backdrop_url'   => null,
                            'tmdb_id'        => null,
                            'jikan_id'       => null,
                            'year'           => date('Y'),
                            'rating'         => null,
                            'total_episodes' => 1,
                            'country'        => null,
                            'language'       => null,
                            'genres'         => [],
                        ]
                    ]];
                }
            } catch (\Exception $e) {
                // Log exception if needed, return empty results for now
            }

            return ['results' => []];
        });
    }
}
