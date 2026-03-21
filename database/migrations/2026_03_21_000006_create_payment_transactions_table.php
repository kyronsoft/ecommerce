<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_ref')->unique();
            $table->string('gateway')->default('epayco');
            $table->string('status')->default('pending');
            $table->decimal('amount', 14, 2);
            $table->string('currency', 10)->default('COP');
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->json('confirmation_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
