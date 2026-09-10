<?php
// database/migrations/xxxx_add_fingerprint_to_visitor_tracking.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('visitor_tracking', function (Blueprint $table) {
            if (!Schema::hasColumn('visitor_tracking', 'fingerprint')) {
                $table->string('fingerprint')->nullable()->unique()->after('id');
            }
        });
    }

    public function down()
    {
        Schema::table('visitor_tracking', function (Blueprint $table) {
            $table->dropColumn('fingerprint');
        });
    }
};