<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\TrailerController;
use App\Http\Controllers\WatchProgressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\VisitorAnalyticsController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\SeriesController as AdminSeriesController;
use App\Http\Controllers\Admin\HeroSlideController as AdminHeroSlideController;
use App\Http\Controllers\Admin\TrailerController as AdminTrailerController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;


/*
|--------------------------------------------------------------------------
| Public Routes (No Auth Required)
|--------------------------------------------------------------------------
*/

Route::get('/', [MovieController::class, 'home'])->name('home');
Route::get('/trailers', [TrailerController::class, 'index'])->name('trailers');
Route::get('/trailers/{slug}', [TrailerController::class, 'show'])->name('trailers.show');
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{slug}', [MovieController::class, 'show'])->name('movies.show');

Route::get('/series', [MovieController::class, 'seriesIndex'])->name('series.index');
Route::get('/series/{id}', [MovieController::class, 'showSeries'])->name('series.show');
Route::get('/genre/{genre}', [MovieController::class, 'genre'])->name('genre.show');
Route::get('/sitemap.xml', [MovieController::class, 'sitemap'])->name('sitemap');
Route::get('/api/search', [MovieController::class, 'apiSearch'])->name('api.search');

// Watch progress (Continue Watching) - login required
Route::post('/watch/progress', [WatchProgressController::class, 'store'])->name('watch.progress');

// View counting (fires from player JS after ~5s of playback) - session guarded
Route::post('/view-track', [ViewController::class, 'track'])->name('view.track');



Route::get('/download/movie/{id}', [DownloadController::class, 'downloadMovie'])->name('download.movie');
Route::get('/download/stream/{id}', [DownloadController::class, 'streamMovie'])->name('stream.movie');
Route::get('/download/series/{id}', [DownloadController::class, 'downloadSeries'])->name('download.series');
Route::get('/download/series/{id}/season/{season}/episode/{episode}', [DownloadController::class, 'downloadSeries'])->name('download.series.episode');

// Interactions (favorites, reactions, comments) - login required
Route::post('/interactions/favorite-toggle', [InteractionController::class, 'toggleFavorite'])->name('interactions.favorite');
Route::post('/interactions/react', [InteractionController::class, 'react'])->name('interactions.react');
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
});




Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Admin routes (protected)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('movies', AdminMovieController::class);
    Route::post('movies/{movie}/toggle-status', [AdminMovieController::class, 'toggleStatus'])->name('movies.toggle-status');
    Route::get('export', [AdminController::class, 'export'])->name('movies.export');

    Route::resource('series', AdminSeriesController::class);
    Route::get('series/{series}/episodes', [AdminSeriesController::class, 'episodes'])->name('series.episodes');
    Route::post('series/{series}/episodes', [AdminSeriesController::class, 'addEpisode'])->name('series.add-episode');
    Route::get('series/{series}/episodes/create', [AdminSeriesController::class, 'createEpisode'])->name('series.episodes.create');
    Route::post('series/{series}/episodes', [AdminSeriesController::class, 'storeEpisode'])->name('series.episodes.store');
    Route::get('series/{series}/episodes/{episode}/edit', [AdminSeriesController::class, 'editEpisode'])->name('series.episodes.edit');
    Route::put('series/{series}/episodes/{episode}', [AdminSeriesController::class, 'updateEpisode'])->name('series.episodes.update');
    Route::delete('series/{series}/episodes/{episode}', [AdminSeriesController::class, 'destroyEpisode'])->name('series.episodes.destroy');
    Route::post('series/{series}/toggle-status', [AdminSeriesController::class, 'toggleStatus'])->name('series.toggle-status');

    Route::resource('hero-slides', AdminHeroSlideController::class);
    Route::post('hero-slides/{heroSlide}/toggle-status', [AdminHeroSlideController::class, 'toggleStatus'])->name('hero-slides.toggle-status');

    Route::resource('trailers', AdminTrailerController::class);
    Route::post('trailers/{trailer}/toggle-status', [AdminTrailerController::class, 'toggleStatus'])->name('trailers.toggle-status');

    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');

    Route::get('/analytics', [VisitorAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/realtime', [VisitorAnalyticsController::class, 'realtime'])->name('analytics.realtime');
    Route::get('/analytics/clear', [VisitorAnalyticsController::class, 'clearData'])->name('analytics.clear');

    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});