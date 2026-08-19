<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watermark_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable(); // logo studio, kalau dipakai sbg watermark
            $table->string('text_overlay')->nullable(); // mis. "© Nama Studio Anda"
            $table->enum('type', ['logo', 'text', 'both'])->default('text');
            $table->enum('position', [
                'top-left', 'top-right', 'top-center',
                'center', 'bottom-left', 'bottom-right', 'bottom-center', 'tiled',
            ])->default('tiled');
            $table->unsignedTinyInteger('opacity')->default(40); // persen, 0-100
            $table->unsignedInteger('font_size')->default(28);
            $table->string('text_color')->default('#FFFFFF');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watermark_settings');
    }
};
