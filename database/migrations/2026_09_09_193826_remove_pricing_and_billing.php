<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::dropIfExists('orders');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->integer('price')->default(0)->after('views');
        });

        Schema::table('series', function (Blueprint $table) {
            $table->integer('price')->default(0)->after('seasons_count');
        });
    }
};