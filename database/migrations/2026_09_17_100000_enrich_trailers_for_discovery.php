<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trailers', function (Blueprint $table) {
            if (!Schema::hasColumn('trailers', 'trailer_type')) {
                $table->string('trailer_type', 60)->default('Official Trailer');
            }
            if (!Schema::hasColumn('trailers', 'duration')) {
                $table->unsignedInteger('duration')->nullable();
            }
            if (!Schema::hasColumn('trailers', 'release_date')) {
                $table->date('release_date')->nullable();
            }
            if (!Schema::hasColumn('trailers', 'year')) {
                $table->unsignedSmallInteger('year')->nullable();
            }
            if (!Schema::hasColumn('trailers', 'genre')) {
                $table->string('genre', 60)->nullable();
            }
            if (!Schema::hasColumn('trailers', 'language')) {
                $table->string('language', 60)->nullable();
            }
            if (!Schema::hasColumn('trailers', 'country')) {
                $table->string('country', 60)->nullable();
            }
            if (!Schema::hasColumn('trailers', 'featured')) {
                $table->boolean('featured')->default(false);
            }
            if (!Schema::hasColumn('trailers', 'trending')) {
                $table->boolean('trending')->default(false);
            }
            if (!Schema::hasColumn('trailers', 'trailer_of_the_day')) {
                $table->boolean('trailer_of_the_day')->default(false);
            }
            if (!Schema::hasIndex('trailers', 'trailers_genre_index')) {
                $table->index('genre');
            }
            if (!Schema::hasIndex('trailers', 'trailers_release_date_index')) {
                $table->index('release_date');
            }
        });

        if (!Schema::hasTable('trailer_views')) {
            Schema::create('trailer_views', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('trailer_id')->index();
                $table->timestamp('viewed_at')->useCurrent();

                $table->foreign('trailer_id')->references('id')->on('trailers')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('trailer_views');

        Schema::table('trailers', function (Blueprint $table) {
            foreach ([
                'trailer_type',
                'duration',
                'release_date',
                'year',
                'genre',
                'language',
                'country',
                'featured',
                'trending',
                'trailer_of_the_day',
            ] as $column) {
                if (Schema::hasColumn('trailers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};