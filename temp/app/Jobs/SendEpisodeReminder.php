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

        // 1. Send Email Notification (Available to all users)
        if ($user->canUseEmail() && $user->email) {
            $this->sendEmail($user, $show, $this->episode);
        }

        // 2. Send SMS Notification (Premium/Admin only)
        if ($user->canUseSms() && $user->phone) {
            $this->sendSms($user, $show, $this->episode);
        }
    }

    protected function sendEmail($user, $show, $episode): void
    {
        try {
            $mailable = new EpisodeReminderMail($episode);

            if ($user->smtpSetting && $user->smtpSetting->is_active) {
                // Use per-user custom SMTP settings
                $mailer = $user->smtpSetting->buildMailer();
                $mailer->to($user->email)->send($mailable);
            } else {
                // Fall back to system default SMTP dynamically loaded from system_settings
                config([
                    'mail.mailers.smtp.host'       => \App\Models\SystemSetting::get('system_mail_host', config('mail.mailers.smtp.host')),
                    'mail.mailers.smtp.port'       => \App\Models\SystemSetting::get('system_mail_port', config('mail.mailers.smtp.port')),
                    'mail.mailers.smtp.username'   => \App\Models\SystemSetting::get('system_mail_user', config('mail.mailers.smtp.username')),
                    'mail.mailers.smtp.password'   => \App\Models\SystemSetting::get('system_mail_pass', config('mail.mailers.smtp.password')),
                    'mail.mailers.smtp.encryption' => \App\Models\SystemSetting::get('system_mail_enc', config('mail.mailers.smtp.encryption')),
                    'mail.from.address'            => \App\Models\SystemSetting::get('system_mail_from', config('mail.from.address')),
                    'mail.from.name'               => \App\Models\SystemSetting::get('system_mail_name', config('mail.from.name')),
                ]);
                
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
            // Determine URL and payload (Custom vs System)
            if ($smsSetting && $smsSetting->is_active) {
                $url = $smsSetting->gateway_url;
                $payload = $smsSetting->buildPayload($user->phone, $message);
                $method = 'POST'; // Users default to POST for now
            } else {
                $url = \App\Models\SystemSetting::get('system_sms_url');
                if (!$url) throw new \Exception("No SMS gateway configured.");
                
                $params = json_decode(\App\Models\SystemSetting::get('system_sms_params', '{}'), true) ?? [];
                // Inject destination phone and message
                $params['to'] = $user->phone; // Most generic API mapping
                $params['message'] = $message;
                $payload = $params;
                $method = \App\Models\SystemSetting::get('system_sms_method', 'POST');
            }

            if (strtoupper($method) === 'GET') {
                $response = Http::get($url, $payload);
            } else {
                $response = Http::asJson()->post($url, $payload);
            }

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
