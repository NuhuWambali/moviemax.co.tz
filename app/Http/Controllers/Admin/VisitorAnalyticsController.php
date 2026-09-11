<?php
// app/Http/Controllers/Admin/VisitorAnalyticsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $days = $request->get('days', 30);
        
        // Basic stats
        $totalVisitors = VisitorTracking::count();
        $totalPageViews = VisitorTracking::sum('visit_count');
        
        // Week and month stats
        $weekVisitors = VisitorTracking::where('created_at', '>=', now()->subDays(7))->count();
        $monthVisitors = VisitorTracking::where('created_at', '>=', now()->subDays(30))->count();
        
        // Calculate bounce rate (single page visits)
        $singlePageVisits = VisitorTracking::where('visit_count', 1)->count();
        $bounceRate = $singlePageVisits > 0 ? round(($singlePageVisits / max($totalVisitors, 1)) * 100, 1) : 0;
        
        // Calculate average session time (in minutes)
        $avgSessionMinutes = 0;
        if ($totalVisitors > 0) {
            $totalMinutes = VisitorTracking::sum(DB::raw('TIMESTAMPDIFF(MINUTE, created_at, last_visit)'));
            $avgSessionMinutes = round($totalMinutes / $totalVisitors, 1);
        }
        
        $stats = [
            'total_visitors' => $totalVisitors,
            'total_page_views' => $totalPageViews,
            'today_visitors' => VisitorTracking::whereDate('created_at', today())->count(),
            'today_views' => VisitorTracking::whereDate('last_visit', today())->sum('visit_count'),
            'average_views_per_visitor' => round($totalPageViews / max($totalVisitors, 1), 1),
            'week_visitors' => $weekVisitors,
            'month_visitors' => $monthVisitors,
            'bounce_rate' => $bounceRate,
            'avg_session_minutes' => $avgSessionMinutes,
        ];
        
        // Daily data for chart
        $dailyData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $uniqueVisitors = VisitorTracking::whereDate('created_at', $date)->count();
            $pageViews = VisitorTracking::whereDate('last_visit', $date)->sum('visit_count');
            $dailyData[] = [
                'date' => $date->format('M d'),
                'visitors' => $uniqueVisitors,
                'views' => $pageViews
            ];
        }
        
        // Device distribution - Get actual data from database
        $devices = VisitorTracking::select('device_type', DB::raw('count(*) as total'))
            ->whereNotNull('device_type')
            ->where('device_type', '!=', '')
            ->groupBy('device_type')
            ->get();
        
        // Operating Systems distribution
        $operatingSystems = VisitorTracking::select('os', DB::raw('count(*) as total'))
            ->whereNotNull('os')
            ->where('os', '!=', '')
            ->groupBy('os')
            ->orderBy('total', 'desc')
            ->get();
        
        // Browser distribution - Get actual data from database
        $browsers = VisitorTracking::select('browser', DB::raw('count(*) as total'))
            ->whereNotNull('browser')
            ->where('browser', '!=', '')
            ->groupBy('browser')
            ->orderBy('total', 'desc')
            ->get();
        
        // Top pages
        $topPages = VisitorTracking::select('page_url', DB::raw('count(*) as views'))
            ->whereNotNull('page_url')
            ->groupBy('page_url')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();
        
        // Recent visitors - Get latest records
        $recentVisitors = VisitorTracking::orderBy('last_visit', 'desc')
            ->simplePaginate(100);
        
        // Hourly activity - Last 24 hours
        $hourlyActivity = [];
        $maxHourlyVisitors = 0;
        for ($i = 23; $i >= 0; $i--) {
            $hour = now()->subHours($i);
            $count = VisitorTracking::where('last_visit', '>=', $hour->copy()->startOfHour())
                ->where('last_visit', '<', $hour->copy()->endOfHour())
                ->count();
            $hourlyActivity[] = [
                'hour' => $hour->format('g A'),
                'visitors' => $count
            ];
            if ($count > $maxHourlyVisitors) {
                $maxHourlyVisitors = $count;
            }
        }
        
        // Country distribution
        $countries = VisitorTracking::select('country', DB::raw('count(*) as total'))
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->groupBy('country')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.analytics.index', compact(
            'stats', 'dailyData', 'devices', 'browsers', 
            'topPages', 'recentVisitors', 'hourlyActivity', 
            'countries', 'days', 'operatingSystems'
        ));
    }
    
    public function realtime()
    {
        // Count unique visitors in last 5 minutes
        $onlineNow = VisitorTracking::where('last_visit', '>=', now()->subMinutes(5))
            ->distinct('fingerprint')
            ->count('fingerprint');
        
        // If no fingerprint column, use session_id or IP
        if ($onlineNow == 0) {
            $onlineNow = VisitorTracking::where('last_visit', '>=', now()->subMinutes(5))
                ->distinct('ip_address')
                ->count('ip_address');
        }
        
        // Get recent activity for the last 5 minutes
        $recentActivity = VisitorTracking::where('last_visit', '>=', now()->subMinutes(5))
            ->orderBy('last_visit', 'desc')
            ->limit(10)
            ->get()
            ->map(function($visit) {
                return [
                    'time' => $visit->last_visit ? $visit->last_visit->diffForHumans() : 'Just now',
                    'page' => $visit->page_url,
                    'device' => $visit->device_type,
                    'browser' => $visit->browser,
                ];
            });
        
        return response()->json([
            'online_now' => $onlineNow,
            'recent_activity' => $recentActivity
        ]);
    }
    
    public function clearData()
    {
        // Keep only last 90 days
        $deleted = VisitorTracking::where('created_at', '<', now()->subDays(90))->delete();
        
        $message = $deleted > 0 ? "Cleared {$deleted} old visitor records!" : "No old records to clear.";
        
        return redirect()->back()->with('success', $message);
    }
    
    // Get visitor details for a specific visitor
    public function visitorDetails($id)
    {
        $visitor = VisitorTracking::findOrFail($id);
        
        return response()->json([
            'ip' => $visitor->ip_address,
            'browser' => $visitor->browser,
            'os' => $visitor->os,
            'device' => $visitor->device_type,
            'first_visit' => $visitor->created_at->format('Y-m-d H:i:s'),
            'last_visit' => $visitor->last_visit ? $visitor->last_visit->format('Y-m-d H:i:s') : 'N/A',
            'total_visits' => $visitor->visit_count,
            'pages_visited' => $visitor->page_url,
            'user_agent' => $visitor->user_agent
        ]);
    }
    
    // Export analytics data
    public function export()
    {
        $data = VisitorTracking::orderBy('created_at', 'desc')->get();
        
        $csvFileName = 'visitor_analytics_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['ID', 'IP Address', 'Browser', 'OS', 'Device', 'Page URL', 'Visit Count', 'First Visit', 'Last Visit']);
            
            // Add data rows
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->ip_address,
                    $row->browser,
                    $row->os,
                    $row->device_type,
                    $row->page_url,
                    $row->visit_count,
                    $row->created_at,
                    $row->last_visit
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}