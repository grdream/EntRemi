<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id',
        'season_no',
        'episode_no',
        'title',
        'description',
        'air_datetime',
        'duration_minutes',
        'thumbnail_url',
        'youtube_link',
        'is_aired',
        'notified',
    ];

    protected function casts(): array
    {
        return [
            'air_datetime'     => 'datetime',
            'is_aired'         => 'boolean',
            'notified'         => 'boolean',
            'season_no'        => 'integer',
            'episode_no'       => 'integer',
            'duration_minutes' => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function show(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Show::class);
    }

    public function reminders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function notificationLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /** Episodes not yet aired and not yet notified — used by the scheduler. */
    public function scopePending(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_aired', false)->where('notified', false);
    }

    /** Episodes whose air_datetime falls within a given UTC window. */
    public function scopeInWindow(
        \Illuminate\Database\Eloquent\Builder $query,
        \Carbon\Carbon $from,
        \Carbon\Carbon $to
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->whereBetween('air_datetime', [$from, $to]);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function label(): string
    {
        $season = $this->season_no ? "S{$this->season_no}E{$this->episode_no}" : "Ep {$this->episode_no}";
        return $this->title ? "{$season} — {$this->title}" : $season;
    }

    public function airTimeForUser(string $timezone): string
    {
        return $this->air_datetime->setTimezone($timezone)->format('D, M d Y \a\t g:i A T');
    }
}
