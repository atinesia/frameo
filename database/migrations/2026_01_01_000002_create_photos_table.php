<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            // kode unik untuk pencarian manual oleh pengguna, mis. "WD-0245"
            $table->string('code')->unique();
            // path di disk 'originals' (private, tidak bisa diakses langsung)
            $table->string('original_path');
            // path di disk 'watermarked' (public, yang tampil di galeri)
            $table->string('watermark_path');
            // path thumbnail kecil untuk grid, biar loading cepat
            $table->string('thumbnail_path')->nullable();
            $table->decimal('price', 12, 2);
            $table->unsignedBigInteger('file_size')->nullable(); // bytes, untuk info sebelum download
            $table->string('original_extension')->default('jpg');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('purchase_count')->default(0);
            $table->timestamps();

            $table->index(['album_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
