<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'customer_name', 'customer_email', 'customer_phone',
        'subtotal', 'unique_code', 'total_amount', 'status', 'payment_method',
        'tripay_reference', 'tripay_merchant_ref', 'tripay_checkout_url',
        'paid_at', 'expired_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'unique_code' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function isPaid(): bool
    {
        return in_array($this->status, ['paid', 'delivered']);
    }
}
