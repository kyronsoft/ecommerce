<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_ref',
        'gateway',
        'status',
        'customer_notification_status',
        'amount',
        'currency',
        'request_payload',
        'response_payload',
        'confirmation_payload',
        'customer_notified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'request_payload' => 'array',
        'response_payload' => 'array',
        'confirmation_payload' => 'array',
        'customer_notified_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function saleCommissions(): HasMany
    {
        return $this->hasMany(SaleCommission::class);
    }
}
