// database/migrations/2024_xx_xx_xxxxxx_add_series_support_to_movies_table.php

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('movies', function (Blueprint $table) {
            // Add series support
            $table->string('type')->default('movie'); // movie, series
            $table->string('season')->nullable(); // e.g., "Season 1"
            $table->string('episode')->nullable(); // e.g., "Episode 1"
            $table->string('episode_title')->nullable(); // e.g., "Pilot"
            $table->unsignedBigInteger('series_id')->nullable(); // link episodes to a series
            $table->integer('episode_number')->nullable();
            $table->integer('season_number')->nullable();
            
            // Add index for faster queries
            $table->index('type');
            $table->index('genre');
            $table->index('series_id');
        });
    }

    public function down()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn(['type', 'season', 'episode', 'episode_title', 'series_id', 'episode_number', 'season_number']);
        });
    }
};