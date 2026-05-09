<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'show_id',
        'episode_id',
        'remind_before_minutes',
        'channels',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'channels'              => 'array',
            'is_active'             => 'boolean',
            'remind_before_minutes' => 'integer',
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

    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /** Human-readable timing label. */
    public function timingLabel(): string
    {
        $minutes = $this->remind_before_minutes;
        if ($minutes < 60) return "{$minutes} minutes before";
        if ($minutes === 60) return '1 hour before';
        if ($minutes < 1440) return ($minutes / 60) . ' hours before';
        return '1 day before';
    }

    public function notifiesEmail(): bool
    {
        return in_array('email', $this->channels ?? []);
    }

    public function notifiesSms(): bool
    {
        return in_array('sms', $this->channels ?? []);
    }
}
