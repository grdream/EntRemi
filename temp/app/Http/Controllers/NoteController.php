<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\WatchlistNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $show = Show::where('slug', $slug)->where('user_id', auth()->id())->firstOrFail();

        $data = $request->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        WatchlistNote::create([
            'user_id' => auth()->id(),
            'show_id' => $show->id,
            'note'    => $data['note'],
        ]);

        return back()->with('success', 'Note added successfully.');
    }

    public function destroy(string $slug, WatchlistNote $note): RedirectResponse
    {
        abort_if($note->user_id !== auth()->id(), 403);
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }
}
