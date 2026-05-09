<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'show_id',
        'episode_id',
        'channel',
        'status',
        'message',
        'error_message',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function show(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Show::class);
    }

    public function episode(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeSent(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'failed');
    }

    public function scopePending(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeForChannel(\Illuminate\Database\Eloquent\Builder $query, string $channel): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('channel', $channel);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'sent'    => 'badge-success',
            'failed'  => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 badge',
            'pending' => 'badge-warning',
            default   => 'badge-brand',
        };
    }

    public function channelIcon(): string
    {
        return match($this->channel) {
            'email' => '✉',
            'sms'   => '📱',
            default => '🔔',
        };
    }
}
