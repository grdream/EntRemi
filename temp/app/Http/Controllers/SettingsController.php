<?php

namespace App\Http\Controllers;

use App\Models\UserSmtpSetting;
use App\Models\UserSmsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function notifications(): View
    {
        $user     = auth()->user();
        $smtp     = $user->smtpSetting;
        $sms      = $user->smsSetting;

        return view('settings.notifications', compact('user', 'smtp', 'sms'));
    }

    public function testSmtp(Request $request)
    {
        $user = auth()->user();
        $smtp = $user->smtpSetting;

        if (!$smtp) {
            return back()->with('smtp_error', 'No SMTP settings configured yet.');
        }

        try {
            $mailer = $smtp->buildMailer();
            $mailer->to($user->email)->send(
                new \Illuminate\Mail\Message(
                    new \Symfony\Component\Mime\Email()
                )
            );

            // Simpler approach: use raw transport test
            $transport = \Symfony\Component\Mailer\Transport::fromDsn(
                'smtp://' . urlencode($smtp->username) . ':' . urlencode($smtp->decryptedPassword())
                . '@' . $smtp->host . ':' . $smtp->port
            );
            $transport->start();

            $smtp->update(['tested_at' => now()]);
            return back()->with('smtp_success', 'SMTP connection successful! Test email can be sent.');
        } catch (\Exception $e) {
            return back()->with('smtp_error', 'SMTP test failed: ' . $e->getMessage());
        }
    }

    public function testSms(Request $request)
    {
        $user = auth()->user();
        $sms  = $user->smsSetting;

        if (!$sms) {
            return back()->with('sms_error', 'No SMS settings configured yet.');
        }

        if (!$user->phone) {
            return back()->with('sms_error', 'Please add your phone number in your profile first.');
        }

        try {
            $payload  = $sms->buildPayload($user->phone, 'WatchList Reminder: SMS test successful! ✓');
            $response = \Illuminate\Support\Facades\Http::asJson()->post($sms->gateway_url, $payload);

            if ($response->successful()) {
                $sms->update(['tested_at' => now()]);
                return back()->with('sms_success', 'SMS sent successfully to ' . $user->phone);
            }

            return back()->with('sms_error', 'SMS gateway error: ' . $response->status() . ' — ' . Str::limit($response->body(), 100));
        } catch (\Exception $e) {
            return back()->with('sms_error', 'SMS test failed: ' . $e->getMessage());
        }
    }
}
