<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Episode;
use App\Jobs\SendEpisodeReminder;
use Carbon\Carbon;

class CheckUpcomingEpisodes extends Command
{
    protected $signature = 'app:check-upcoming-episodes';
    protected $description = 'Scan for upcoming episodes and dispatch reminder notifications.';

    public function handle()
    {
        $now = Carbon::now('UTC');
        // We notify 15 minutes before the air time
        $targetTime = $now->copy()->addMinutes(15);
        // Don't notify for things more than 24 hours in the past
        $thresholdTime = $now->copy()->subHours(24);

        $episodes = Episode::with(['show.user.smtpSetting', 'show.user.smsSetting'])
            ->where('is_aired', false)
            ->where('notified', false)
            ->whereNotNull('air_datetime')
            ->where('air_datetime', '<=', $targetTime)
            ->where('air_datetime', '>=', $thresholdTime)
            ->get();

        $count = 0;
        foreach ($episodes as $episode) {
            $user = $episode->show->user;

            // Only dispatch if at least one notification method is enabled
            if ($user->email_notifications || $user->sms_notifications) {
                SendEpisodeReminder::dispatch($episode);
            }

            // Mark as notified to prevent duplicate processing
            $episode->notified = true;
            
            if ($episode->air_datetime <= $now) {
                $episode->is_aired = true;
            }
            
            $episode->save();
            $count++;
        }

        $this->info("Dispatched {$count} episode reminders.");
    }
}
