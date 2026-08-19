<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('event_date')->nullable();
            $table->string('cover_photo')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            // harga default per foto di album ini, bisa dioverride di level foto
            $table->decimal('default_price', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
