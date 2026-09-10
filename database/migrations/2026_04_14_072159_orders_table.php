<?php
// database/migrations/xxxx_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('payment_method'); // mpesa, tigopesa, airtelmoney, halopesa
            $table->string('payment_reference')->nullable(); // transaction ID if provided
            $table->string('screenshot_path')->nullable(); // uploaded screenshot
            $table->unsignedBigInteger('amount'); // in TZS
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('download_token', 64)->unique()->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->string('download_ip')->nullable(); // bind download to IP
            $table->unsignedInteger('download_count')->default(0);
            $table->unsignedInteger('max_downloads')->default(3);
            $table->text('admin_note')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
