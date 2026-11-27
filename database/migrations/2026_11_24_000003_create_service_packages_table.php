<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name', 30);
            $table->string('slug')->unique();
            $table->string('rank_key', 40);
            $table->unsignedInteger('price');
            $table->string('unit', 20)->default('star');
            $table->string('badge')->nullable();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('emblem')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_packages');
    }
};
