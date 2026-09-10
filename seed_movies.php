<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Movie;
use App\Models\Series;

// Game of Thrones
$gameOfThrones = Series::create([
    'title' => 'Game of Thrones',
    'description' => 'Nine noble families fight for control over the lands of Westeros, while an ancient enemy returns after being dormant for millennia.',
    'genre' => 'Fantasy',
    'release_year' => 2011,
    'language' => 'English',
    'rating' => 'TV-MA',
    'poster_path' => 'https://image.tmdb.org/t/p/w500/7WUHnWGx5jj145A2HM6SwhGfUOl.jpg',
    'trailer_url' => 'https://www.youtube.com/watch?v=KPLWWIOCOOQ',
    'backdrop_path' => null,
    'seasons_count' => 8,
    'is_active' => true
]);
echo "✓ Game of Thrones created (ID: {$gameOfThrones->id})\n";

// Stranger Things
$strangerThings = Series::create([
    'title' => 'Stranger Things',
    'description' => 'When a young boy vanishes, a small town uncovers a mystery involving secret experiments, terrifying supernatural forces, and one strange little girl.',
    'genre' => 'Sci-Fi',
    'release_year' => 2016,
    'language' => 'English',
    'rating' => 'TV-14',
    'poster_path' => 'https://image.tmdb.org/t/p/w500/49WJfeNqOBPLjPjdFsjpNszCjpm.jpg',
    'trailer_url' => 'https://www.youtube.com/watch?v=b9EkMc79ZSU',
    'backdrop_path' => null,
    'seasons_count' => 4,
    'is_active' => true
]);
echo "✓ Stranger Things created (ID: {$strangerThings->id})\n";

// Horror Movies
Movie::create([
    'title' => 'The Conjuring',
    'description' => 'Paranormal investigators Ed and Lorraine Warren work to help a family terrorized by a dark presence in their farmhouse.',
    'genre' => 'Horror',
    'release_year' => 2013,
    'duration' => '1h 52min',
    'language' => 'English',
    'rating' => 'R',
    'views' => 15000,
    'poster_path' => 'https://image.tmdb.org/t/p/w500/xf9wuDcqlUPWABZNeDKPbZUjWx0.jpg',
    'file_path' => 'movies/the_conjuring.mp4',
    'file_size' => '1.8 GB',
    'trailer_url' => 'https://www.youtube.com/watch?v=k10ETZ41q5o',
    'is_active' => true,
    'download_count' => 4500,
    'type' => 'movie'
]);
echo "✓ The Conjuring added\n";

Movie::create([
    'title' => 'Insidious',
    'description' => 'A family discovers that dark spirits have invaded their home after their son inexplicably falls into an endless sleep.',
    'genre' => 'Horror',
    'release_year' => 2010,
    'duration' => '1h 43min',
    'language' => 'English',
    'rating' => 'PG-13',
    'views' => 12000,
    'poster_path' => 'https://image.tmdb.org/t/p/w500/xf9wuDcqlUPWABZNeDKPbZUjWx0.jpg',
    'file_path' => 'movies/insidious.mp4',
    'file_size' => '1.6 GB',
    'trailer_url' => 'https://www.youtube.com/watch?v=62rpZcMYa0A',
    'is_active' => true,
    'download_count' => 3800,
    'type' => 'movie'
]);
echo "✓ Insidious added\n";

// Romance Movies
Movie::create([
    'title' => 'The Notebook',
    'description' => 'A poor yet passionate young man falls in love with a rich young woman, giving her a sense of freedom.',
    'genre' => 'Romance',
    'release_year' => 2004,
    'duration' => '2h 3min',
    'language' => 'English',
    'rating' => 'PG-13',
    'views' => 25000,
    'poster_path' => 'https://image.tmdb.org/t/p/w500/rNzQyW4f8B8cQeg7Dgj3n6eT5k9.jpg',
    'file_path' => 'movies/the_notebook.mp4',
    'file_size' => '1.5 GB',
    'trailer_url' => 'https://www.youtube.com/watch?v=BjJcYdEOI0k',
    'is_active' => true,
    'download_count' => 8900,
    'type' => 'movie'
]);
echo "✓ The Notebook added\n";

// War Movies
Movie::create([
    'title' => '1917',
    'description' => 'Two young British soldiers during the First World War are given an impossible mission: deliver a message deep in enemy territory.',
    'genre' => 'War',
    'release_year' => 2019,
    'duration' => '1h 59min',
    'language' => 'English',
    'rating' => 'R',
    'views' => 22000,
    'poster_path' => 'https://image.tmdb.org/t/p/w500/iZf0KyrE25z1sage4SYFLCCrMiY.jpg',
    'file_path' => 'movies/1917.mp4',
    'file_size' => '2.1 GB',
    'trailer_url' => 'https://www.youtube.com/watch?v=UcmZN0Mbl04',
    'is_active' => true,
    'download_count' => 3200,
    'type' => 'movie'
]);
echo "✓ 1917 added\n";

// Action Movies
Movie::create([
    'title' => 'John Wick: Chapter 4',
    'description' => 'John Wick uncovers a path to defeating The High Table. But before he can earn his freedom, Wick must face off against a new enemy.',
    'genre' => 'Action',
    'release_year' => 2023,
    'duration' => '2h 49min',
    'language' => 'English',
    'rating' => 'R',
    'views' => 28000,
    'poster_path' => 'https://image.tmdb.org/t/p/w500/vZloFAK7NmvMGKE7VkF5UHaz0I.jpg',
    'file_path' => 'movies/john_wick_4.mp4',
    'file_size' => '3.2 GB',
    'trailer_url' => 'https://www.youtube.com/watch?v=qEVUtrk8_B4',
    'is_active' => true,
    'download_count' => 6800,
    'type' => 'movie'
]);
echo "✓ John Wick 4 added\n";

// Sci-Fi Movies
Movie::create([
    'title' => 'Dune: Part Two',
    'description' => 'Paul Atreides unites with Chani and the Fremen while seeking revenge against the conspirators who destroyed his family.',
    'genre' => 'Sci-Fi',
    'release_year' => 2024,
    'duration' => '2h 46min',
    'language' => 'English',
    'rating' => 'PG-13',
    'views' => 45000,
    'poster_path' => 'https://image.tmdb.org/t/p/w500/8b8R8l88Qje9dnbOEu9pS1X8T.jpg',
    'file_path' => 'movies/dune_2.mp4',
    'file_size' => '2.4 GB',
    'trailer_url' => 'https://www.youtube.com/watch?v=Way9Dexny3w',
    'is_active' => true,
    'download_count' => 12400,
    'type' => 'movie'
]);
echo "✓ Dune 2 added\n";

echo "\n========== SUMMARY ==========\n";
echo "Series: " . Series::count() . "\n";
echo "Movies: " . Movie::where('type', 'movie')->count() . "\n";
echo "Total: " . Movie::count() . "\n";
echo "\n✓ All done!\n";
