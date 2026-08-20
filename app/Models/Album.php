<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'event_date', 'cover_photo',
        'description', 'is_public', 'default_price',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_public' => 'boolean',
        'default_price' => 'decimal:2',
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function bundle(): HasOne
    {
        return $this->hasOne(Bundle::class);
    }
}
