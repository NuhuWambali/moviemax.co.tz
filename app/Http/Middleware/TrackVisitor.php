<?php
// app/Http/Middleware/TrackVisitor.php

namespace App\Http\Middleware;

use App\Models\VisitorTracking;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;

class TrackVisitor
{
    /** Per-process throttle so bursts never hammer the DB (session is the primary gate). */
    protected static array $processThrottle = [];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            // Only track GET HTML requests
            if ($request->ajax() || $request->wantsJson() || !$request->isMethod('get')) {
                return $response;
            }

            $path = $request->path();

            // Skip analytics, admin, auth, member and utility endpoints
            foreach (['realtime', 'admin', 'login', 'register', 'logout', 'profile', 'favorites', 'download', 'interactions', 'sitemap', 'up'] as $segment) {
                if (str_starts_with($path, $segment)) {
                    return $response;
                }
            }

            // Throttle tracking: once per 60 seconds per IP per process …
            $ip = $request->ip();
            $now = time();
            if ((static::$processThrottle[$ip] ?? 0) > $now) {
                return $response;
            }

            // … and once per 60 seconds per browser session when a session is available.
            if ($request->hasSession()) {
                $lastTracked = $request->session()->get('mm_track_ts', 0);
                if ($lastTracked > 0 && $now - $lastTracked < 60) {
                    return $response;
                }
            }

            // Initialize Agent
            $agent = new Agent();
            $agent->setUserAgent($request->userAgent());

            // Create a unique fingerprint using IP + Browser + OS
            $browser = $agent->browser();
            $os = $agent->platform();
            $fingerprint = md5($ip . $browser . $os);

            $currentUrl = $request->fullUrl();

            // Check if visitor exists by fingerprint
            $visitor = VisitorTracking::where('fingerprint', $fingerprint)->first();

            if ($visitor) {
                $lastVisit = $visitor->last_visit;
                $shouldIncrement = !$lastVisit || $lastVisit->diffInMinutes(now()) >= 5;

                $updateData = [
                    'last_visit' => now(),
                    'page_url' => $currentUrl,
                    'device_type' => $agent->deviceType(),
                    'browser' => $browser,
                    'os' => $os,
                    'user_agent' => $request->userAgent(),
                    'country' => $this->getCountryFromIp($ip),
                ];

                if ($shouldIncrement) {
                    $updateData['visit_count'] = $visitor->visit_count + 1;
                }

                $visitor->update($updateData);
            } else {
                // Create new visitor
                VisitorTracking::create([
                    'fingerprint' => $fingerprint,
                    'ip_address' => $ip,
                    'user_agent' => $request->userAgent(),
                    'page_url' => $currentUrl,
                    'referrer' => $request->headers->get('referer'),
                    'device_type' => $agent->deviceType(),
                    'browser' => $browser,
                    'os' => $os,
                    'is_unique' => true,
                    'visit_count' => 1,
                    'country' => $this->getCountryFromIp($ip),
                    'last_visit' => now(),
                ]);
            }

            static::$processThrottle[$ip] = $now + 60;
            if ($request->hasSession()) {
                $request->session()->put('mm_track_ts', $now);
            }
        } catch (\Throwable $e) {
            // Never let tracking break the page
        }

        return $response;
    }

    private function getCountryFromIp($ip): string
    {
        // Skip local IPs
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost']) ||
            str_starts_with($ip, '192.168.') ||
            str_starts_with($ip, '10.')) {
            return 'Local';
        }

        // Cache the geo result per IP for 24 hours to avoid external calls on every request
        return Cache::remember('visitor_country_' . md5($ip), now()->addDay(), function () use ($ip) {
            try {
                $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country");
                if ($response) {
                    $data = json_decode($response, true);
                    if ($data && ($data['status'] ?? '') === 'success') {
                        return $data['country'];
                    }
                }
                return 'Unknown';
            } catch (\Exception $e) {
                return 'Unknown';
            }
        });
    }
}