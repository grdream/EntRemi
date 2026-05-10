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

        $file    = $request->file('json_file');
        $content = file_get_contents($file->getRealPath());
        $data    = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Invalid JSON file format. Please check the file and try again.');
        }

        if (!is_array($data)) {
            return back()->with('error', 'The JSON file must contain an array of objects.');
        }

        $userId   = auth()->id();
        $imported = 0;
        $skipped  = 0;
        $failed   = 0;
        $errors   = [];

        foreach ($data as $index => $item) {
            $rowNum = $index + 1;

            if (!isset($item['title']) || empty(trim($item['title']))) {
                $errors[] = "Row {$rowNum}: Missing required field 'title' — skipped.";
                $skipped++;
                continue;
            }

            try {
                $title = trim($item['title']);

                // Map type
                $type = isset($item['type']) ? strtolower($item['type']) : 'anime';
                if (!in_array($type, ['drama', 'movie', 'anime', 'tv_series', 'anime_movie', 'other'])) {
                    $type = 'other';
                }

                // Map status (supports MAL integers)
                $rawStatus = $item['status'] ?? 'plan_to_watch';
                if (is_numeric($rawStatus)) {
                    $status = match ((int)$rawStatus) {
                        1 => 'watching',
                        2 => 'completed',
                        3 => 'on_hold',
                        4 => 'dropped',
                        6 => 'plan_to_watch',
                        default => 'plan_to_watch',
                    };
                } else {
                    $status = strtolower($rawStatus);
                    if (!in_array($status, ['watching', 'completed', 'on_hold', 'dropped', 'plan_to_watch'])) {
                        $status = 'plan_to_watch';
                    }
                }

                // Check if already exists
                $exists = Show::where('user_id', $userId)->where('title', $title)->exists();
                if ($exists) {
                    $errors[] = "Row {$rowNum}: \"{$title}\" already in your watchlist — skipped.";
                    $skipped++;
                    continue;
                }

                $slug = Show::generateUniqueSlug($title);

                Show::create([
                    'user_id'        => $userId,
                    'slug'           => $slug,
                    'title'          => $title,
                    'type'           => $type,
                    'status'         => $status,
                    'total_episodes' => $item['episodes'] ?? $item['total_episodes'] ?? null,
                    'rating'         => $item['score'] ?? $item['rating'] ?? null,
                    'description'    => $item['synopsis'] ?? $item['description'] ?? null,
                    'year'           => $item['year'] ?? null,
                    'country'        => $item['country'] ?? null,
                    'poster_url'     => $item['image_url'] ?? $item['poster_url'] ?? null,
                ]);

                $imported++;
            } catch (\Throwable $e) {
                $errors[] = "Row {$rowNum}: \"" . ($item['title'] ?? '?') . "\" — " . $e->getMessage();
                $failed++;
            }
        }

        return back()->with('import_result', [
            'imported' => $imported,
            'skipped'  => $skipped,
            'failed'   => $failed,
            'errors'   => $errors,
        ]);
    }
}

