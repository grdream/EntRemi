<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class UserSmtpSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'host',
        'port',
        'username',
        'password',   // stored as encrypted ciphertext
        'encryption',
        'from_address',
        'from_name',
        'is_active',
        'tested_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'tested_at' => 'datetime',
            'port'      => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Encryption helpers ───────────────────────────────────────────────────

    /**
     * Encrypt and set the password attribute.
     * Usage: $setting->setPassword('plaintext')
     */
    public function setPassword(string $plaintext): void
    {
        $this->password = Crypt::encryptString($plaintext);
    }

    /**
     * Decrypt and return the password.
     */
    public function decryptedPassword(): string
    {
        try {
            return Crypt::decryptString($this->password);
        } catch (\Illuminate\Contracts\Encryption\DecryptException) {
            return '';
        }
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Build the runtime mailer config array for Mail::mailer().
     */
    public function toMailerConfig(): array
    {
        return [
            'transport'  => 'smtp',
            'host'       => $this->host,
            'port'       => $this->port,
            'encryption' => $this->encryption === 'none' ? null : $this->encryption,
            'username'   => $this->username,
            'password'   => $this->decryptedPassword(),
        ];
    }

    public function isTested(): bool
    {
        return $this->tested_at !== null;
    }
}
