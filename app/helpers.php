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

if (!function_exists('format_duration')) {
    /**
     * Format a stored duration value for display.
     * Accepts raw seconds (e.g. 7200) or an already human-readable string
     * (e.g. "1h 40min", "1h 30m", "45 minutes") and normalizes it.
     */
    function format_duration($value)
    {
        $value = trim((string) ($value ?? ''));

        if ($value === '') {
            return null;
        }

        // Numeric value -> treat as seconds
        if (is_numeric($value)) {
            $total = max(0, (int) $value);
            $hours = intdiv($total, 3600);
            $minutes = intdiv($total % 3600, 60);

            if ($hours > 0 && $minutes > 0) {
                return $hours . 'h ' . $minutes . 'min';
            }
            if ($hours > 0) {
                return $hours . 'h';
            }
            if ($minutes > 0) {
                return $minutes . 'min';
            }

            return $total > 0 ? $total . 'sec' : '0min';
        }

        // Already readable integer-like text? e.g. "90" as string
        if (preg_match('/^\d+$/', $value)) {
            return format_duration((int) $value);
        }

        // Normalize common human formats to a single style: "2h 35min"
        if (preg_match('/(\d+)\s*h(?:ours?)?/i', $value, $hm)) {
            $mins = 0;
            if (preg_match('/(\d+)\s*m(?:in(?:ute)?s?)?/i', $value, $mm)) {
                $mins = (int) $mm[1];
            }
            $hours = (int) $hm[1];
            return $mins > 0 ? $hours . 'h ' . $mins . 'min' : $hours . 'h';
        }

        if (preg_match('/(\d+)\s*m(?:in(?:ute)?s?)?/i', $value, $mm)) {
            return (int) $mm[1] . 'min';
        }

        if (preg_match('/(\d+)\s*s(?:ec(?:ond)?s?)?/i', $value, $sm)) {
            return format_duration((int) $sm[1]);
        }

        return $value;
    }
}