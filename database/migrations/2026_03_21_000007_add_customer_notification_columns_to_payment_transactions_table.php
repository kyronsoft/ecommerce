<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->string('customer_notification_status')->nullable()->after('status');
            $table->timestamp('customer_notified_at')->nullable()->after('confirmation_payload');
        });
    }

    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropColumn(['customer_notification_status', 'customer_notified_at']);
        });
    }
};
