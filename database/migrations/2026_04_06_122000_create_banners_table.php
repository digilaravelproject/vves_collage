<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $col) {
            $col->id();
            $col->string('title')->nullable();
            $col->text('subtitle')->nullable();
            $col->string('button_text')->nullable();
            $col->string('button_link')->nullable();
            $col->string('media_path');
            $col->enum('media_type', ['image', 'video'])->default('image');
            $col->integer('order')->default(0);
            $col->boolean('is_active')->default(true);
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
