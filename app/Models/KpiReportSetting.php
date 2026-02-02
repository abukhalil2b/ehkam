<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiReportSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'setting_key',
        'setting_value',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    /**
     * Get setting value and decode JSON if needed.
     */
    public function getValueAttribute()
    {
        if (!$this->setting_value) {
            return null;
        }

        $decoded = json_decode($this->setting_value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $this->setting_value;
    }

    /**
     * Set setting value with JSON encoding.
     */
    public function setValueAttribute($value)
    {
        $this->attributes['setting_value'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Get a setting for a user.
     */
    public static function getSetting($userId, $key, $default = null)
    {
        $setting = static::where('user_id', $userId)
            ->where('setting_key', $key)
            ->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting for a user.
     */
    public static function setSetting($userId, $key, $value)
    {
        return static::updateOrCreate(
            ['user_id' => $userId, 'setting_key' => $key],
            ['setting_value' => is_array($value) ? json_encode($value) : $value]
        );
    }
}
