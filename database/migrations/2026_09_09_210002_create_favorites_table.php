<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('visitor_key')->nullable()->index();
            $table->string('favoritable_type');
            $table->unsignedBigInteger('favoritable_id');
            $table->timestamps();

            $table->unique(['user_id', 'favoritable_type', 'favoritable_id'], 'fav_user_unique');
            $table->unique(['visitor_key', 'favoritable_type', 'favoritable_id'], 'fav_guest_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};