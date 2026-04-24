<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'payment_transaction_id',
        'store_id',
        'product_id',
        'sale_amount',
        'marketplace_commission_rate',
        'marketplace_commission_amount',
        'marketplace_commission_vat_rate',
        'marketplace_commission_vat_amount',
        'epayco_percentage_rate',
        'epayco_percentage_amount',
        'epayco_fixed_fee_amount',
        'epayco_total_fee_amount',
        'total_deduction_amount',
        'entrepreneur_net_amount',
        'payment_status',
        'meta',
    ];

    protected $casts = [
        'sale_amount' => 'decimal:2',
        'marketplace_commission_rate' => 'decimal:4',
        'marketplace_commission_amount' => 'decimal:2',
        'marketplace_commission_vat_rate' => 'decimal:4',
        'marketplace_commission_vat_amount' => 'decimal:2',
        'epayco_percentage_rate' => 'decimal:4',
        'epayco_percentage_amount' => 'decimal:2',
        'epayco_fixed_fee_amount' => 'decimal:2',
        'epayco_total_fee_amount' => 'decimal:2',
        'total_deduction_amount' => 'decimal:2',
        'entrepreneur_net_amount' => 'decimal:2',
        'meta' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function paymentTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
