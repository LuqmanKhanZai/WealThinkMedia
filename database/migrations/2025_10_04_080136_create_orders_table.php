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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // buyer
            $table->string('stripe_payment_intent_id')->nullable();  // pi_xxx
            $table->string('stripe_charge_id')->nullable();          // ch_xxx
            $table->string('stripe_customer_id')->nullable();        // cus_xxx
            $table->string('currency', 10)->default('usd');
            $table->decimal('amount', 10, 2); // total order amount
            $table->string('status')->default('pending'); // pending, succeeded, failed, refunded
            $table->json('products')->nullable(); // store cart items (id, qty, price)
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
