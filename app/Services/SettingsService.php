<?php

namespace App\Services;

use App\Setting;
use Cache;
use Config;

class SettingsService
{

    public static function makeSettings()
    {
        Cache::forget('settings');

        Cache::rememberForever('settings', function () {
            return Setting::get()->pluck('value', 'key')->toArray();
        });

        self::storeSettings();
    }

    public static function storeSettings()
    {
        if (!Cache::has('settings')) {
            self::makeSettings();
        }

        Config::set('settings', Cache::get('settings'));
    }
}