<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id',
        'pattern',
        'days_of_week',
        'air_time',
        'timezone',
        'episodes_per_slot',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'days_of_week'     => 'array',
            'start_date'       => 'date',
            'end_date'         => 'date',
            'is_active'        => 'boolean',
            'episodes_per_slot'=> 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function show(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Show::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /** Human-readable schedule summary. */
    public function summaryLabel(): string
    {
        return match($this->pattern) {
            'daily'           => "Daily at {$this->air_time}",
            'weekly'          => 'Weekly on ' . implode(', ', $this->days_of_week ?? []) . " at {$this->air_time}",
            'bi_weekly'       => 'Bi-weekly on ' . implode(' & ', $this->days_of_week ?? []) . " at {$this->air_time}",
            'twice_per_week'  => 'Twice per week',
            'monthly'         => "Monthly at {$this->air_time}",
            'irregular'       => 'Irregular / Manual',
            'movie_one_time'  => 'One-time (Movie)',
            default           => ucfirst(str_replace('_', ' ', $this->pattern)),
        };
    }
}
