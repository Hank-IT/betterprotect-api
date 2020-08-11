<?php

namespace App\Services;

use App\Models\Setting;

class Settings
{
    /**
     * Get a setting from the store.
     *
     * @param $key
     * @param $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        if (empty($value = optional(Setting::where('key', '=', $key)->first())->value)) {
            return $default;
        }

        return $value;
    }

    /**
     * Determine if the store has a setting.
     *
     * @param $key
     * @return mixed
     */
    public static function has($key)
    {
        return optional(Setting::where('key', '=', $key)->first())->exists;
    }

    /**
     * Add a setting to the store.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public static function set($key, $value)
    {
        if (self::has($key)) {
            return Setting::where('key', '=', $key)->update(['value' => $value]);
        }

        return Setting::insert(['key' => $key, 'value' => $value]);
    }

    /**
     * Remove a setting from the store.
     *
     * @param $key
     * @return bool|null
     * @throws \Exception
     */
    public static function forget($key)
    {
        return Setting::where('key', '=', $key)->delete();
    }
}