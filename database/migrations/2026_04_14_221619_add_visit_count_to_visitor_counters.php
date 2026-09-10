<?php
// database/migrations/xxxx_add_visit_count_to_visitor_counters.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('visitor_tracking', function (Blueprint $table) {
            $table->integer('visit_count')->default(1);
            $table->dateTime('last_visit')->nullable();
        });
    }

    public function down()
    {
        Schema::table('visitor_tracking', function (Blueprint $table) {
            $table->dropColumn('visit_count');
            $table->dropColumn('last_visit');
        });
    }
};