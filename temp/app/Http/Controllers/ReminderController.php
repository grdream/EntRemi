<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Show;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $show = Show::where('slug', $slug)->where('user_id', auth()->id())->firstOrFail();

        $data = $request->validate([
            'remind_before_minutes' => ['required', 'integer', 'in:30,60,120,1440'],
            'channels'              => ['required', 'array', 'min:1'],
            'channels.*'            => ['in:email,sms'],
        ]);

        // Upsert: one reminder per show (update if exists, else create)
        Reminder::updateOrCreate(
            ['user_id' => auth()->id(), 'show_id' => $show->id, 'episode_id' => null],
            [
                'remind_before_minutes' => $data['remind_before_minutes'],
                'channels'              => $data['channels'],
                'is_active'             => true,
            ]
        );

        return back()->with('success', 'Reminder configured successfully.');
    }

    public function update(Request $request, string $slug, Reminder $reminder): RedirectResponse
    {
        abort_if($reminder->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'remind_before_minutes' => ['required', 'integer', 'in:30,60,120,1440'],
            'channels'              => ['required', 'array', 'min:1'],
            'channels.*'            => ['in:email,sms'],
            'is_active'             => ['boolean'],
        ]);

        $reminder->update($data);

        return back()->with('success', 'Reminder updated.');
    }

    public function destroy(string $slug, Reminder $reminder): RedirectResponse
    {
        abort_if($reminder->user_id !== auth()->id(), 403);
        $reminder->delete();
        return back()->with('success', 'Reminder removed.');
    }
}
