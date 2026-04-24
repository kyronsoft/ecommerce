<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\SaleCommission;
use Illuminate\Support\Collection;

class SaleCommissionService
{
    public function syncForOrder(Order $order): Collection
    {
        $order->loadMissing(['items.product.store', 'paymentTransactions']);

        $transaction = $order->paymentTransactions
            ->sortByDesc('created_at')
            ->first();

        $isApprovedSale = ($transaction?->status === 'approved') || $order->status === 'paid';

        if (! $isApprovedSale) {
            SaleCommission::query()->where('order_id', $order->id)->delete();

            return collect();
        }

        $items = $order->items
            ->filter(fn (OrderItem $item) => (float) $item->total > 0)
            ->values();

        if ($items->isEmpty()) {
            return collect();
        }

        $saleTotal = round((float) $items->sum(fn (OrderItem $item) => (float) $item->total), 2);

        if ($saleTotal <= 0) {
            return collect();
        }

        $epaycoPercentageRate = $this->epaycoPercentageRate();
        $marketplaceCommissionRate = $this->marketplaceCommissionRate();
        $marketplaceCommissionVatRate = $this->marketplaceCommissionVatRate();
        $epaycoFixedFee = $this->epaycoFixedFee();

        $lastIndex = $items->count() - 1;
        $allocatedFixedFee = 0.0;

        $commissions = $items->map(function (OrderItem $item, int $index) use (
            $items,
            $saleTotal,
            $transaction,
            $epaycoPercentageRate,
            $marketplaceCommissionRate,
            $marketplaceCommissionVatRate,
            $epaycoFixedFee,
            $lastIndex,
            &$allocatedFixedFee
        ) {
            $saleAmount = round((float) $item->total, 2);
            $marketplaceCommissionAmount = $this->roundMoney($saleAmount * $marketplaceCommissionRate);
            $marketplaceCommissionVatAmount = $this->roundMoney($marketplaceCommissionAmount * $marketplaceCommissionVatRate);
            $epaycoPercentageAmount = $this->roundMoney($saleAmount * $epaycoPercentageRate);

            if ($index === $lastIndex) {
                $epaycoFixedFeeAmount = max(0, $this->roundMoney($epaycoFixedFee - $allocatedFixedFee));
            } else {
                $epaycoFixedFeeAmount = $this->roundMoney($epaycoFixedFee * ($saleAmount / $saleTotal));
                $allocatedFixedFee += $epaycoFixedFeeAmount;
            }

            $epaycoTotalFeeAmount = $this->roundMoney($epaycoPercentageAmount + $epaycoFixedFeeAmount);
            $totalDeductionAmount = $this->roundMoney(
                $marketplaceCommissionAmount + $marketplaceCommissionVatAmount + $epaycoTotalFeeAmount
            );
            $entrepreneurNetAmount = $this->roundMoney($saleAmount - $totalDeductionAmount);

            return SaleCommission::query()->updateOrCreate(
                ['order_item_id' => $item->id],
                [
                    'order_id' => $item->order_id,
                    'payment_transaction_id' => $transaction?->id,
                    'store_id' => $item->product?->store_id,
                    'product_id' => $item->product_id,
                    'sale_amount' => $saleAmount,
                    'marketplace_commission_rate' => $marketplaceCommissionRate * 100,
                    'marketplace_commission_amount' => $marketplaceCommissionAmount,
                    'marketplace_commission_vat_rate' => $marketplaceCommissionVatRate * 100,
                    'marketplace_commission_vat_amount' => $marketplaceCommissionVatAmount,
                    'epayco_percentage_rate' => $epaycoPercentageRate * 100,
                    'epayco_percentage_amount' => $epaycoPercentageAmount,
                    'epayco_fixed_fee_amount' => $epaycoFixedFeeAmount,
                    'epayco_total_fee_amount' => $epaycoTotalFeeAmount,
                    'total_deduction_amount' => $totalDeductionAmount,
                    'entrepreneur_net_amount' => $entrepreneurNetAmount,
                    'payment_status' => $this->resolvePaymentStatus($order, $transaction),
                    'meta' => [
                        'order_number' => $order->number,
                        'product_name' => $item->name,
                        'product_sku' => $item->sku,
                        'quantity' => $item->quantity,
                        'unit_price' => (float) $item->unit_price,
                        'source' => [
                            'marketplace_commission_rate_decimal' => $marketplaceCommissionRate,
                            'marketplace_commission_vat_rate_decimal' => $marketplaceCommissionVatRate,
                            'epayco_percentage_rate_decimal' => $epaycoPercentageRate,
                            'epayco_fixed_fee' => $epaycoFixedFee,
                            'epayco_reference' => 'Agregador todo en uno',
                        ],
                    ],
                ]
            );
        });

        return $commissions;
    }

    public function syncForPaidOrders(): void
    {
        Order::query()
            ->where('status', 'paid')
            ->with(['items.product.store', 'paymentTransactions'])
            ->chunkById(100, function ($orders): void {
                foreach ($orders as $order) {
                    $this->syncForOrder($order);
                }
            });
    }

    public function marketplaceCommissionRate(): float
    {
        return ((float) config('marketplace.sales.marketplace_commission_rate', 5)) / 100;
    }

    public function marketplaceCommissionVatRate(): float
    {
        return ((float) config('marketplace.sales.marketplace_commission_vat_rate', 19)) / 100;
    }

    public function epaycoPercentageRate(): float
    {
        return ((float) config('epayco.sales_fee.aggregator_percentage_rate', 2.68)) / 100;
    }

    public function epaycoFixedFee(): float
    {
        return (float) config('epayco.sales_fee.aggregator_fixed_fee', 900);
    }

    private function resolvePaymentStatus(Order $order, ?PaymentTransaction $transaction): string
    {
        if ($transaction?->status) {
            return (string) $transaction->status;
        }

        return $order->status === 'paid' ? 'approved' : (string) ($order->status ?: 'pending');
    }

    private function roundMoney(float $value): float
    {
        return round($value, 2);
    }
}
