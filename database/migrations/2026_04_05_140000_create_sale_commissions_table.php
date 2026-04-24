<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('payment_transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('store_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('sale_amount', 14, 2);
            $table->decimal('marketplace_commission_rate', 8, 4);
            $table->decimal('marketplace_commission_amount', 14, 2);
            $table->decimal('marketplace_commission_vat_rate', 8, 4);
            $table->decimal('marketplace_commission_vat_amount', 14, 2);
            $table->decimal('epayco_percentage_rate', 8, 4);
            $table->decimal('epayco_percentage_amount', 14, 2);
            $table->decimal('epayco_fixed_fee_amount', 14, 2);
            $table->decimal('epayco_total_fee_amount', 14, 2);
            $table->decimal('total_deduction_amount', 14, 2);
            $table->decimal('entrepreneur_net_amount', 14, 2);
            $table->string('payment_status')->default('pending');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['store_id', 'payment_status']);
            $table->index(['order_id', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_commissions');
    }
};
