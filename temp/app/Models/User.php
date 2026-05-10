<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'timezone',
        'avatar',
        'email_notifications',
        'sms_notifications',
        'sms_gateway_enabled',
        'dark_mode',
        'plan',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'     => 'datetime',
            'password'              => 'hashed',
            'email_notifications'   => 'boolean',
            'sms_notifications'     => 'boolean',
            'sms_gateway_enabled'   => 'boolean',
            'dark_mode'             => 'boolean',
            'is_active'             => 'boolean',
        ];
    }

    // ─────────────────── Relationships (to be expanded in Phase 2) ───────────────────

    public function shows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Show::class);
    }

    public function reminders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Reminder::class);
    }

    public function notificationLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\NotificationLog::class);
    }

    public function smtpSetting(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\UserSmtpSetting::class);
    }

    public function smsSetting(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\UserSmsSetting::class);
    }

    // ─────────────────── Helpers ───────────────────

    /**
     * Get the user's avatar URL or a generated initials placeholder.
     */
    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return '';
    }

    /**
     * Return the user's timezone for display, falling back to UTC.
     */
    public function tz(): string
    {
        return $this->timezone ?: 'UTC';
    }

    // ─────────────────── Plan / Role Helpers ───────────────────

    /** Is this user an admin (matched by ADMIN_EMAIL env)? */
    public function isAdmin(): bool
    {
        return $this->email === config('app.admin_email', env('ADMIN_EMAIL', ''));
    }

    /** Is the user on the premium plan? */
    public function isPremium(): bool
    {
        return $this->plan === 'premium' || $this->isAdmin();
    }

    /** Is the user on the free plan? */
    public function isFree(): bool
    {
        return !$this->isPremium();
    }

    /** Is the account suspended? */
    public function isSuspended(): bool
    {
        return !($this->is_active ?? true);
    }

    /**
     * Can this user receive SMS reminders?
     * Premium/admin only — free users get email only.
     */
    public function canUseSms(): bool
    {
        return $this->isPremium() && ($this->sms_notifications ?? false);
    }

    /**
     * Can this user receive email reminders?
     * All plans (free + premium).
     */
    public function canUseEmail(): bool
    {
        return (bool) ($this->email_notifications ?? true);
    }

    /** Plan badge label for UI. */
    public function planLabel(): string
    {
        return $this->isPremium() ? 'Premium' : 'Free';
    }
}
