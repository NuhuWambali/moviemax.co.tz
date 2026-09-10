<?php

if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return \App\Models\SiteSetting::get($key, $default);
    }
}

if (!function_exists('number_format_short')) {
    function number_format_short($n) {
        if ($n < 1000) {
            return $n;
        }

        if ($n < 1000000) {
            return round($n / 1000, 1) . 'K';
        }

        if ($n < 1000000000) {
            return round($n / 1000000, 1) . 'M';
        }

        return round($n / 1000000000, 1) . 'B';
    }
}