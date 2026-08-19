<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatermarkSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_path', 'text_overlay', 'type', 'position',
        'opacity', 'font_size', 'text_color', 'is_active',
    ];

    protected $casts = [
        'opacity' => 'integer',
        'font_size' => 'integer',
        'is_active' => 'boolean',
    ];

    public static function active(): ?self
    {
        return static::where('is_active', true)->latest()->first();
    }
}
