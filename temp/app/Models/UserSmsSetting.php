<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class UserSmsSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gateway_url',
        'api_key',      // stored encrypted
        'sender_id',
        'extra_params', // JSON key-value pairs for ViserLab SMSLab
        'is_active',
        'tested_at',
    ];

    protected function casts(): array
    {
        return [
            'extra_params' => 'array',
            'is_active'    => 'boolean',
            'tested_at'    => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Encryption helpers ───────────────────────────────────────────────────

    public function setApiKey(string $plaintext): void
    {
        $this->api_key = Crypt::encryptString($plaintext);
    }

    public function decryptedApiKey(): string
    {
        if (empty($this->api_key)) return '';
        try {
            return Crypt::decryptString($this->api_key);
        } catch (\Illuminate\Contracts\Encryption\DecryptException) {
            return '';
        }
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Build the POST payload for the ViserLab SMSLab gateway.
     * Merges gateway defaults + extra_params + phone + message.
     */
    public function buildPayload(string $phone, string $message): array
    {
        $base = array_merge($this->extra_params ?? [], [
            'phone'   => $phone,
            'message' => $message,
        ]);

        if (!empty($this->decryptedApiKey())) {
            $base['api_key'] = $this->decryptedApiKey();
        }

        if (!empty($this->sender_id)) {
            $base['sender_id'] = $this->sender_id;
        }

        return $base;
    }

    public function isTested(): bool
    {
        return $this->tested_at !== null;
    }
}
