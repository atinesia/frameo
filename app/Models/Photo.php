<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_id', 'code', 'original_path', 'watermark_path',
        'thumbnail_path', 'price', 'file_size', 'original_extension',
        'is_active', 'view_count', 'purchase_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function bundles(): BelongsToMany
    {
        return $this->belongsToMany(Bundle::class);
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // URL publik untuk foto berwatermark (yang tampil di galeri)
    public function getWatermarkUrlAttribute(): string
    {
        return Storage::disk('watermarked')->url($this->watermark_path);
    }

    // PENTING: tidak ada accessor untuk URL original di sini secara sengaja.
    // Akses ke file asli hanya boleh lewat DownloadController + signed token,
    // jangan pernah expose original_path langsung ke view/frontend.
}
