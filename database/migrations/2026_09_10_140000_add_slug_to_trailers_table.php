<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trailers', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        DB::table('trailers')->orderBy('id')->each(function ($row) {
            $base = Str::slug($row->title) ?: 'trailer';
            $slug = $base;
            $i = 1;
            while (DB::table('trailers')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = $base . '-' . ($i++);
            }
            DB::table('trailers')->where('id', $row->id)->update(['slug' => $slug]);
        });

        Schema::table('trailers', function (Blueprint $table) {
            $table->unique('slug', 'trailers_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('trailers', function (Blueprint $table) {
            $table->dropUnique('trailers_slug_unique');
            $table->dropColumn('slug');
        });
    }
};