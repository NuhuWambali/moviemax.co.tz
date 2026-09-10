<?php
// database/migrations/xxxx_create_movies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('genre')->nullable();
            $table->year('release_year')->nullable();
            $table->string('duration')->nullable(); // e.g. "2h 15min"
            $table->string('language')->default('English');
            $table->string('rating')->nullable();
            $table->string('views')->nullable();  // PG, R, etc
            $table->unsignedBigInteger('price'); // in TZS
            $table->string('poster_path')->nullable();
            $table->string('file_path'); // private storage
            $table->string('file_size')->nullable(); // human readable
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
