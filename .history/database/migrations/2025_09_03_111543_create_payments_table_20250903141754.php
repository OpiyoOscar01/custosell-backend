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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique(); // PAY-2025-001
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices'); // If payment is for an invoice
            $table->foreignId('order_id')->nullable()->constrained('orders'); // If direct payment for order
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->enum('type', ['payment', 'refund', 'partial_refund'])->default('payment');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('payment_date');
            $table->string('payment_method'); // cash, credit_card, bank_transfer, paypal, etc.
            $table->string('reference_number')->nullable(); // Bank reference, transaction ID, etc.
            $table->text('notes')->nullable();
            $table->json('gateway_response')->nullable(); // Payment gateway response data
            $table->string('gateway_transaction_id')->nullable();
            $table->decimal('fee_amount', 10, 2)->default(0); // Payment processing fee
            $table->decimal('net_amount', 15, 2); // Amount minus fees
            $table->timestamps();
            
            // Indexes
            $table->index(['workspace_id', 'status']);
            $table->index(['customer_id']);
            $table->index(['invoice_id']);
            $table->index(['order_id']);
            $table->index(['payment_number']);
            $table->index(['payment_date']);
            $table->index(['gateway_transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
