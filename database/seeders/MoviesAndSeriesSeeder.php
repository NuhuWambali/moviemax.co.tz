<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MoviesAndSeriesSeeder extends Seeder
{
    public function run(): void
    {
        // 5 sample movies
        $movies = [
            // Movie file_path cannot be null – use a placeholder poster path
            [
                'title'       => 'From',
                'slug'        => 'from',
                'description' => 'A gripping thriller series available for streaming.',
                'type'        => 'movie',
                'duration'    => 5400,
                'views'       => 12450,
                'rating'      => 8.2,
                'file_path'   => '/images/posters/dummy-poster.png',
            ],
            [
                'title'       => 'The Last Stand',
                'slug'        => 'the-last-stand',
                'description' => 'Action-packed movie about a small town defending itself.',
                'type'        => 'movie',
                'duration'    => 7200,
                'views'       => 9830,
                'rating'      => 7.5,
                'file_path'   => '/images/posters/dummy-poster.png',
            ],
            [
                'title'       => 'Midnight Call',
                'slug'        => 'midnight-call',
                'description' => 'Mystery drama that keeps you on the edge of your seat.',
                'type'        => 'movie',
                'duration'    => 6000,
                'views'       => 7650,
                'rating'      => 7.9,
                'file_path'   => '/images/posters/dummy-poster.png',
            ],
            [
                'title'       => 'Summer Heat',
                'slug'        => 'summer-heat',
                'description' => 'Romantic comedy set on a coastal town during peak summer.',
                'type'        => 'movie',
                'duration'    => 4800,
                'views'       => 6520,
                'rating'      => 6.8,
                'file_path'   => '/images/posters/dummy-poster.png',
            ],
            [
                'title'       => 'Dark Woods',
                'slug'        => 'dark-woods',
                'description' => 'Horror film about a group of friends lost in a forbidding forest.',
                'type'        => 'movie',
                'duration'    => 5100,
                'views'       => 5430,
                'rating'      => 6.2,
                'file_path'   => '/images/posters/dummy-poster.png',
            ],
        ];

        DB::table('movies')->insert($movies);

        // 1 sample series
        DB::table('series')->insert([
            'title'       => 'Game of Thrones',
            'description' => 'Political intrigue and epic battles for the iron throne.',
            'seasons_count' => 8,
            'poster_path' => null,
        ]);
    }
}