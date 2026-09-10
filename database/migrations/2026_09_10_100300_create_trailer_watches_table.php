<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trailer_watches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('trailer_id')->index();
            $table->timestamp('watched_at')->useCurrent();

            $table->unique(['user_id', 'trailer_id'], 'trailer_watch_unique');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('trailer_id')->references('id')->on('trailers')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trailer_watches');
    }
};