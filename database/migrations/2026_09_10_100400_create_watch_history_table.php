<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watch_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('watchable_type');
            $table->unsignedBigInteger('watchable_id');
            $table->timestamp('watched_at')->useCurrent();

            $table->unique(['user_id', 'watchable_type', 'watchable_id'], 'watch_unique');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['watchable_type', 'watchable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watch_history');
    }
};