<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Show extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'type',
        'description',
        'poster_url',
        'backdrop_url',
        'tmdb_id',
        'jikan_id',
        'imdb_id',
        'status',
        'country',
        'language',
        'total_episodes',
        'genres',
        'rating',
        'year',
    ];

    protected function casts(): array
    {
        return [
            'genres'          => 'array',
            'total_episodes'  => 'integer',
        ];
    }

    // ─── Boot: auto-generate slug ────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Show $show) {
            if (empty($show->slug)) {
                $show->slug = static::generateUniqueSlug($show->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;
        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function episodes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Episode::class)->orderBy('season_no')->orderBy('episode_no');
    }

    public function schedule(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Schedule::class)->latestOfMany();
    }

    public function schedules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function reminders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function notificationLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WatchlistNote::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeForUser(\Illuminate\Database\Eloquent\Builder $query, int $userId): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWatching(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'watching');
    }

    public function scopeOfType(\Illuminate\Database\Eloquent\Builder $query, string $type): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('type', $type);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function nextEpisode(): ?Episode
    {
        return $this->episodes()
            ->where('is_aired', false)
            ->orderBy('air_datetime')
            ->first();
    }

    public function posterUrl(): string
    {
        return $this->poster_url ?: asset('images/no-poster.svg');
    }

    public function typeBadgeColor(): string
    {
        return match($this->type) {
            'anime', 'anime_movie' => 'badge-accent',
            'movie'               => 'badge-warning',
            'drama'               => 'badge-brand',
            default               => 'badge-brand',
        };
    }

    public function statusBadgeColor(): string
    {
        return match($this->status) {
            'watching'      => 'badge-success',
            'completed'     => 'badge-brand',
            'on_hold'       => 'badge-warning',
            'dropped'       => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 badge',
            'plan_to_watch' => 'badge-accent',
            default         => 'badge-brand',
        };
    }
}
