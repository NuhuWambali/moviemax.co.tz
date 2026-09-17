<?php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Reaction;
use App\Models\Trailer;
use App\Models\TrailerWatch;
use App\Models\TrailerView;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Basic stats
        $stats = [
            'trailers' => Trailer::count(),
            'active_trailers' => Trailer::where('is_active', true)->count(),
            'users' => User::count(),
            'system_users' => User::whereIn('user_type', ['staff', 'admin'])->count(),
            'total_views' => Trailer::sum('views'),
            'favorites' => Favorite::where('favoritable_type', Trailer::class)->count(),
            'views_today' => TrailerView::whereDate('viewed_at', now()->toDateString())->count(),
            'views_week' => TrailerView::where('viewed_at', '>=', now()->startOfWeek())->count(),
        ];

        // Recent trailers
        $recentTrailers = Trailer::latest()->take(5)->get();

        // Top watched trailers (highest views)
        $topTrailers = Trailer::orderBy('views', 'desc')->take(5)->get();

        // Recently watched (last 5 watch records)
        $recentWatches = TrailerWatch::with('trailer', 'user')->latest('watched_at')->take(5)->get();

        // Monthly trailer views for chart (current year)
        $monthlyViews = [];
        $currentYear = date('Y');

        for ($month = 1; $month <= 12; $month++) {
            $views = Trailer::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('views');
            $monthlyViews[] = $views;
        }

        // Last 12 months rolling data
        $last12Months = [];
        $last12MonthsLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $views = Trailer::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('views');
            $last12Months[] = $views;
            $last12MonthsLabels[] = $date->format('M Y');
        }

        // Comments this week
        $commentsThisWeek = Comment::where('commentable_type', Trailer::class)
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();
        $stats['comments_this_week'] = $commentsThisWeek;

        // Last 14 days of per-day trailer views
        $last14Days = [];
        $last14DaysLabels = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $last14Days[] = TrailerView::whereDate('viewed_at', $day)->count();
            $last14DaysLabels[] = now()->subDays($i)->format('M j');
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentTrailers',
            'topTrailers',
            'recentWatches',
            'monthlyViews',
            'last12Months',
            'last12MonthsLabels',
            'currentYear',
            'last14Days',
            'last14DaysLabels'
        ));
    }

    public function export()
    {
        return back()->with('success', 'Export feature coming soon!');
    }
}