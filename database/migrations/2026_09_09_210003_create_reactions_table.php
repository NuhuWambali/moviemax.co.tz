<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('visitor_key')->nullable()->index();
            $table->string('reactable_type');
            $table->unsignedBigInteger('reactable_id');
            $table->enum('reaction', ['like', 'dislike']);
            $table->timestamps();

            $table->unique(['user_id', 'reactable_type', 'reactable_id'], 'react_user_unique');
            $table->unique(['visitor_key', 'reactable_type', 'reactable_id'], 'react_guest_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};