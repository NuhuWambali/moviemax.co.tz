<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('watch_history', function (Blueprint $table) {
            $table->unsignedInteger('progress_seconds')->default(0)->after('watched_at');
            $table->unsignedInteger('duration_seconds')->default(0)->after('progress_seconds');
        });
    }

    public function down(): void
    {
        Schema::table('watch_history', function (Blueprint $table) {
            $table->dropColumn(['progress_seconds', 'duration_seconds']);
        });
    }
};