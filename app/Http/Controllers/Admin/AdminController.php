<?php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Series;
use App\Models\Trailer;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Basic stats
        $stats = [
            'movies' => Movie::where('type', 'movie')->count(),
            'episodes' => Movie::where('type', 'episode')->count(),
            'series' => Series::count(),
            'trailers' => Trailer::count(),
            'users' => User::count(),
            'system_users' => User::whereIn('user_type', ['staff', 'admin'])->count(),
            'total_downloads' => Movie::sum('download_count'),
        ];
        
        // Recent movies
        $recentMovies = Movie::where('type', 'movie')->latest()->take(5)->get();
        
        // Recent series
        $recentSeries = Series::latest()->take(5)->get();
        
        // Top downloads
        $topDownloads = Movie::orderBy('download_count', 'desc')->take(5)->get();
        
        // Monthly downloads for chart (current year - REAL DATA from database)
        $monthlyDownloads = [];
        $currentYear = date('Y');
        
        for ($month = 1; $month <= 12; $month++) {
            $downloads = Movie::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('download_count');
            $monthlyDownloads[] = $downloads;
        }
        
        // Last 12 months rolling data
        $last12Months = [];
        $last12MonthsLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $downloads = Movie::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('download_count');
            $last12Months[] = $downloads;
            $last12MonthsLabels[] = $date->format('M Y');
        }
        
        return view('admin.dashboard', compact(
            'stats', 
            'recentMovies', 
            'recentSeries', 
            'topDownloads', 
            'monthlyDownloads',
            'last12Months',
            'last12MonthsLabels',
            'currentYear'
        ));
    }

    public function export()
        {
            $movies = Movie::where('type', 'movie')->get();
            // Add export logic here (Excel/CSV)
            return back()->with('success', 'Export feature coming soon!');
        }
}