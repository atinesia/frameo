<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // mis. "Paket 10 Foto Wisuda"
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('photo_count'); // jumlah foto yg boleh dipilih dlm paket ini
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot: foto mana saja yang termasuk pilihan dalam sebuah bundle (opsional,
        // dipakai jika bundle dibatasi ke foto tertentu, bukan bebas pilih dari album)
        Schema::create('bundle_photo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('photo_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['bundle_id', 'photo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundle_photo');
        Schema::dropIfExists('bundles');
    }
};
