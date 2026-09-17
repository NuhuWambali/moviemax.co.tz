<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\TrailerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\VisitorAnalyticsController;
use App\Http\Controllers\Admin\HeroSlideController as AdminHeroSlideController;
use App\Http\Controllers\Admin\TrailerController as AdminTrailerController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;


/*
|--------------------------------------------------------------------------
| Public Routes (No Auth Required)
|--------------------------------------------------------------------------
*/

Route::get('/', [TrailerController::class, 'home'])->name('home');
Route::get('/trailers', [TrailerController::class, 'index'])->name('trailers');
Route::get('/latest', [TrailerController::class, 'index'])->name('latest')->defaults('sort', 'latest');
Route::get('/trending', [TrailerController::class, 'index'])->name('trending')->defaults('sort', 'trending');
Route::get('/genres', [TrailerController::class, 'genres'])->name('genres');
Route::get('/genre/{genre}', [TrailerController::class, 'genre'])->name('genre.show');
Route::get('/trailers/{slug}', [TrailerController::class, 'show'])->name('trailers.show');

Route::get('/sitemap.xml', [TrailerController::class, 'sitemap'])->name('sitemap');
Route::get('/api/search', [TrailerController::class, 'apiSearch'])->name('api.search');
    Route::get('/api/filters', [TrailerController::class, 'apiFilters'])->name('api.filters');

// View counting (fires from player JS after ~5s of playback) - session guarded
Route::post('/view-track', [ViewController::class, 'track'])->name('view.track');

// Interactions (favorites, reactions, comments) - login required
Route::post('/interactions/favorite-toggle', [InteractionController::class, 'toggleFavorite'])->name('interactions.favorite');
Route::post('/interactions/react', [InteractionController::class, 'react'])->name('interactions.react');
Route::get('/interactions/stats', [InteractionController::class, 'stats'])->name('interactions.stats');
Route::post('/interactions/comment', [InteractionController::class, 'storeComment'])->name('interactions.comment');
Route::delete('/interactions/comment/{id}', [InteractionController::class, 'deleteComment'])->name('interactions.comment.delete');
Route::get('/favorites', [InteractionController::class, 'favorites'])->name('favorites');

Route::get('/about', function () {
    return view('about');
})->name('about');

// User profile & account (login required)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/genres/{genre}/follow', [InteractionController::class, 'toggleGenreFollow'])->name('genres.follow');
    Route::get('/notifications', [InteractionController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/read-all', [InteractionController::class, 'readAllNotifications'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [InteractionController::class, 'unreadNotificationsCount'])->name('notifications.unread-count');
});




Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google Sign-In
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');


// Admin routes (protected)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('trailers', AdminTrailerController::class);
    Route::post('trailers/{trailer}/toggle-status', [AdminTrailerController::class, 'toggleStatus'])->name('trailers.toggle-status');

    Route::resource('hero-slides', AdminHeroSlideController::class);
    Route::post('hero-slides/{heroSlide}/toggle-status', [AdminHeroSlideController::class, 'toggleStatus'])->name('hero-slides.toggle-status');

    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');

    Route::get('/analytics', [VisitorAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/realtime', [VisitorAnalyticsController::class, 'realtime'])->name('analytics.realtime');
    Route::get('/analytics/clear', [VisitorAnalyticsController::class, 'clearData'])->name('analytics.clear');
    Route::match(['get', 'post'], '/analytics/export', [VisitorAnalyticsController::class, 'export'])->name('analytics.export');

    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});