<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 30)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_package_id')->constrained()->restrictOnDelete();
            $table->string('package_name');
            $table->string('package_unit', 20);
            $table->unsignedInteger('unit_price');
            $table->unsignedSmallInteger('stars');
            $table->unsignedInteger('total_price');
            $table->string('game_nickname');
            $table->string('game_id', 40);
            $table->string('server_id', 20);
            $table->string('login_method', 30);
            $table->string('whatsapp', 20);
            $table->string('payment_method', 40)->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 30)->default('pending');
            $table->string('payment_status', 30)->default('unpaid');
            $table->text('admin_note')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
