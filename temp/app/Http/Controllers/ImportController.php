<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImportController extends Controller
{
    /**
     * Display the import tool view.
     */
    public function index(): View
    {
        return view('tools.import');
    }

    /**
     * Handle the uploaded JSON file.
     */
    public function store(Request $request)
    {
        $request->validate([
            'json_file' => ['required', 'file', 'mimetypes:application/json,text/plain', 'max:2048'],
        ]);

        $file = $request->file('json_file');
        $content = file_get_contents($file->getRealPath());
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Invalid JSON file format. Please check the file and try again.');
        }

        if (!is_array($data)) {
            return back()->with('error', 'The JSON file must contain an array of objects.');
        }

        $userId = auth()->id();
        $importedCount = 0;

        foreach ($data as $item) {
            if (!isset($item['title'])) {
                continue; // Skip items without a title
            }

            // Map incoming JSON keys to our schema. This handles a generic structure.
            $title = $item['title'];
            $slug = Show::generateUniqueSlug($title);
            
            // Basic mapping logic
            $type = isset($item['type']) ? strtolower($item['type']) : 'anime';
            if (!in_array($type, ['drama', 'movie', 'anime', 'tv_series', 'anime_movie', 'other'])) {
                $type = 'anime'; // fallback
            }

            $status = isset($item['status']) ? strtolower($item['status']) : 'plan_to_watch';
            // Simple mapping for MAL statuses if they use integers
            if (is_numeric($status)) {
                $status = match ((int)$status) {
                    1 => 'watching',
                    2 => 'completed',
                    3 => 'on_hold',
                    4 => 'dropped',
                    6 => 'plan_to_watch',
                    default => 'plan_to_watch',
                };
            } elseif (!in_array($status, ['watching', 'completed', 'on_hold', 'dropped', 'plan_to_watch'])) {
                $status = 'plan_to_watch';
            }

            // Create the record
            Show::firstOrCreate(
                ['user_id' => $userId, 'title' => $title],
                [
                    'slug' => $slug,
                    'type' => $type,
                    'status' => $status,
                    'total_episodes' => $item['episodes'] ?? $item['total_episodes'] ?? null,
                    'rating' => $item['score'] ?? $item['rating'] ?? null,
                    'description' => $item['synopsis'] ?? $item['description'] ?? null,
                    'year' => $item['year'] ?? null,
                    'poster_url' => $item['image_url'] ?? $item['poster_url'] ?? null,
                ]
            );

            $importedCount++;
        }

        return redirect()->route('watchlist.index')
            ->with('success', "Successfully imported {$importedCount} titles to your watchlist.");
    }
}
