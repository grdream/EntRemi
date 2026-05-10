<?php

namespace App\Mail;

use App\Models\Episode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EpisodeReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Episode $episode;

    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    public function envelope(): Envelope
    {
        $showTitle = $this->episode->show->title;
        $epNo = $this->episode->episode_no;
        return new Envelope(
            subject: "Upcoming Episode: {$showTitle} - Episode {$epNo}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.episode-reminder',
            with: [
                'episode' => $this->episode,
                'show'    => $this->episode->show,
                'user'    => $this->episode->show->user,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
