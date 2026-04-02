<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled, refunded
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, failed, refunded
            $table->string('payment_method')->nullable(); // stripe, paypal, cod, bank_transfer
            $table->string('payment_transaction_id')->nullable();
            $table->json('payment_details')->nullable();
            $table->foreignId('currency_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('exchange_rate', 10, 6)->default(1.000000);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->string('shipping_method_name')->nullable();
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();

            // Billing address snapshot
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_company')->nullable();
            $table->string('billing_address_line1')->nullable();
            $table->string('billing_address_line2')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_email')->nullable();

            // Shipping address snapshot
            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->string('shipping_company')->nullable();
            $table->string('shipping_address_line1')->nullable();
            $table->string('shipping_address_line2')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_phone')->nullable();

            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
