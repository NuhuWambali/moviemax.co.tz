<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        $defaults = [
            'site_name'          => 'MOVIEMAX',
            'tagline'            => 'Stream and download the latest movies and TV series in stunning quality. Your entertainment, your way.',
            'contact_email'      => 'support@moviemax.co.tz',
            'contact_phone'      => '+255688349680',
            'whatsapp_number'    => '+255688349680',
            'buy_me_coffee_url'  => '',
            'logo_path'          => '',
        ];

        foreach ($defaults as $key => $value) {
            \Illuminate\Support\Facades\DB::table('site_settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};