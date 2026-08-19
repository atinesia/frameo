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
            $table->string('order_number')->unique(); // mis. INV-20260819-0001, dipakai sbg merchant_ref ke Tripay
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // null = guest checkout
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone'); // format 62xxxx untuk WA
            $table->decimal('subtotal', 12, 2);
            $table->decimal('unique_code', 12, 2)->default(0); // kode unik tambahan biar mudah rekonsiliasi manual jika perlu
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'expired', 'failed', 'delivered'])
                  ->default('pending');
            $table->string('payment_method')->nullable(); // QRIS, BRIVA, dll dari Tripay
            $table->string('tripay_reference')->nullable(); // reference dari Tripay
            $table->string('tripay_merchant_ref')->nullable();
            $table->text('tripay_checkout_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
