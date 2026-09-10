<?php


/*
|==========================================================================
| config/filesystems.php  (add private disk)
|==========================================================================
*/



/*
|==========================================================================
| database/migrations/xxxx_add_is_admin_to_users_table.php
|==========================================================================
*/
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
