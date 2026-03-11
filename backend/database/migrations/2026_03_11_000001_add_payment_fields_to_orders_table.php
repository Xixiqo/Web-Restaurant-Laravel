<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('notes');
            $table->string('payment_status')->default('unpaid')->after('payment_method'); // unpaid, pending, paid, failed, expired
            $table->string('payment_channel')->nullable()->after('payment_status');
            $table->string('xendit_invoice_id')->nullable()->after('payment_channel');
            $table->string('xendit_payment_url', 500)->nullable()->after('xendit_invoice_id');
            $table->timestamp('paid_at')->nullable()->after('xendit_payment_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_status',
                'payment_channel',
                'xendit_invoice_id',
                'xendit_payment_url',
                'paid_at',
            ]);
        });
    }
};
