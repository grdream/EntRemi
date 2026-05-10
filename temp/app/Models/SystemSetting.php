<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    protected $fillable = ['key', 'value'];

    // ─── Static Helpers ───────────────────────────────────────────────────────

    /**
     * Get a setting value by key (with optional default).
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $record = static::where('key', $key)->first();
        return $record ? $record->value : $default;
    }

    /**
     * Set (upsert) a setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Get multiple settings as an associative array.
     */
    public static function getMany(array $keys): array
    {
        return static::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Get all settings as key => value array.
     */
    public static function all($columns = ['*'])
    {
        return parent::all($columns)->pluck('value', 'key')->toArray();
    }
}
