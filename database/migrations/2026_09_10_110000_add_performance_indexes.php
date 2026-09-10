<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('series', function (Blueprint $table) {
            if (!Schema::hasIndex('series', 'series_is_active_genre_index')) {
                $table->index(['is_active', 'genre'], 'series_is_active_genre_index');
            }
            if (!Schema::hasIndex('series', 'series_download_count_index')) {
                $table->index(['download_count'], 'series_download_count_index');
            }
        });

        Schema::table('trailers', function (Blueprint $table) {
            if (!Schema::hasIndex('trailers', 'trailers_is_active_index')) {
                $table->index(['is_active'], 'trailers_is_active_index');
            }
            if (!Schema::hasIndex('trailers', 'trailers_views_index')) {
                $table->index(['views'], 'trailers_views_index');
            }
        });

        Schema::table('hero_slides', function (Blueprint $table) {
            if (!Schema::hasIndex('hero_slides', 'hero_slides_is_active_sort_index')) {
                $table->index(['is_active', 'sort_order'], 'hero_slides_is_active_sort_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('series', function (Blueprint $table) {
            $table->dropIndex('series_is_active_genre_index');
            $table->dropIndex('series_download_count_index');
        });

        Schema::table('trailers', function (Blueprint $table) {
            $table->dropIndex('trailers_is_active_index');
            $table->dropIndex('trailers_views_index');
        });

        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropIndex('hero_slides_is_active_sort_index');
        });
    }
};