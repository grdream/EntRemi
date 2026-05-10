<?php

namespace App\Jobs;

use App\Models\Episode;
use App\Models\NotificationLog;
use App\Mail\EpisodeReminderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEpisodeReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Episode $episode;

    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    public function handle(): void
    {
        $show = $this->episode->show;
        $user = $show->user;

        $user->loadMissing(['smtpSetting', 'smsSetting']);

        // 1. Send Email Notification
        if ($user->email_notifications && $user->email) {
            $this->sendEmail($user, $show, $this->episode);
        }

        // 2. Send SMS Notification
        if ($user->sms_notifications && $user->phone && $user->smsSetting) {
            $this->sendSms($user, $show, $this->episode);
        }
    }

    protected function sendEmail($user, $show, $episode): void
    {
        try {
            $mailable = new EpisodeReminderMail($episode);

            if ($user->smtpSetting && $user->smtpSetting->is_active) {
                // Use per-user SMTP settings
                $mailer = $user->smtpSetting->buildMailer();
                $mailer->to($user->email)->send($mailable);
            } else {
                // Fall back to system default SMTP
                Mail::to($user->email)->send($mailable);
            }

            NotificationLog::create([
                'user_id'    => $user->id,
                'show_id'    => $show->id,
                'episode_id' => $episode->id,
                'channel'    => 'email',
                'status'     => 'sent',
                'message'    => "Email reminder sent for {$show->title} Episode {$episode->episode_no}",
                'sent_at'    => now(),
            ]);

            Log::info("Sent email reminder to {$user->email} for Show: {$show->title} Ep: {$episode->episode_no}");

        } catch (\Exception $e) {
            NotificationLog::create([
                'user_id'       => $user->id,
                'show_id'       => $show->id,
                'episode_id'    => $episode->id,
                'channel'       => 'email',
                'status'        => 'failed',
                'message'       => "Failed to send email for {$show->title} Episode {$episode->episode_no}",
                'error_message' => $e->getMessage(),
                'sent_at'       => null,
            ]);

            Log::error("Failed to send email reminder to {$user->email}: " . $e->getMessage());
        }
    }

    protected function sendSms($user, $show, $episode): void
    {
        $smsSetting = $user->smsSetting;

        $message = "WatchList Reminder: {$show->title} Episode {$episode->episode_no} is airing soon!";

        try {
            // FIX: correct field name is gateway_url (not api_url)
            $url = $smsSetting->gateway_url;

            // Build payload using the model's helper (handles encryption and extra_params)
            $payload = $smsSetting->buildPayload($user->phone, $message);

            // FIX: default to POST unless SMS setting has a specific method stored
            // request_method field doesn't exist in schema, default POST
            $response = Http::asJson()->post($url, $payload);

            if (!$response->successful()) {
                throw new \Exception("SMS Gateway returned status: " . $response->status() . " - " . $response->body());
            }

            NotificationLog::create([
                'user_id'    => $user->id,
                'show_id'    => $show->id,
                'episode_id' => $episode->id,
                'channel'    => 'sms',
                'status'     => 'sent',
                'message'    => "SMS reminder sent to {$user->phone} for {$show->title} Episode {$episode->episode_no}",
                'sent_at'    => now(),
            ]);

            Log::info("Sent SMS reminder to {$user->phone} for Show: {$show->title} Ep: {$episode->episode_no}");

        } catch (\Exception $e) {
            NotificationLog::create([
                'user_id'       => $user->id,
                'show_id'       => $show->id,
                'episode_id'    => $episode->id,
                'channel'       => 'sms',
                'status'        => 'failed',
                'message'       => "Failed to send SMS for {$show->title} Episode {$episode->episode_no}",
                'error_message' => $e->getMessage(),
                'sent_at'       => null,
            ]);

            Log::error("Failed to send SMS reminder to {$user->phone}: " . $e->getMessage());
        }
    }
}
