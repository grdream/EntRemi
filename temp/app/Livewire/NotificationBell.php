<?php

namespace App\Livewire;

use App\Models\NotificationLog;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool  $open       = false;
    public int   $unreadCount = 0;
    public array $recent     = [];

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $logs = NotificationLog::where('user_id', auth()->id())
            ->with('show')
            ->latest()
            ->limit(6)
            ->get();

        $lastRead = session('notifications_last_read', 0);

        $this->unreadCount = NotificationLog::where('user_id', auth()->id())
            ->where('id', '>', $lastRead)
            ->count();

        $this->recent = $logs->map(fn($log) => [
            'id'         => $log->id,
            'show_title' => $log->show->title ?? 'Unknown Show',
            'channel'    => $log->channel,
            'status'     => $log->status,
            'time'       => $log->created_at->diffForHumans(),
            'is_new'     => $log->id > $lastRead,
        ])->toArray();
    }

    public function toggle(): void
    {
        $this->open = !$this->open;
        if ($this->open) {
            $this->markRead();
        }
    }

    public function markRead(): void
    {
        $latest = NotificationLog::where('user_id', auth()->id())->max('id') ?? 0;
        session(['notifications_last_read' => $latest]);
        $this->unreadCount = 0;
        $this->loadNotifications();
    }

    public function close(): void
    {
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
