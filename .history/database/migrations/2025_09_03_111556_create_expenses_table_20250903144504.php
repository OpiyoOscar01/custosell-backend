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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->unique(); // EXP-2025-001
            $table->foreignId('user_id')->constrained('users'); // Who incurred the expense
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('project_id')->nullable()->constrained('projects'); // If project-related
            $table->foreignId('customer_id')->nullable()->constrained('customers'); // If customer-related
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('expense_date');
            $table->string('payment_method')->nullable(); // cash, credit_card, company_card, etc.
            $table->string('vendor')->nullable(); // Vendor/supplier name
            $table->string('receipt_number')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'reimbursed'])->default('draft');
            $table->boolean('is_billable')->default(false); // Can be billed to customer
            $table->boolean('is_reimbursable')->default(true); // Employee can be reimbursed
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 15, 2); // amount + tax
            $table->json('attachments')->nullable(); // Receipt images, documents
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->datetime('approved_at')->nullable();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices'); // If billed to customer
            $table->json('custom_fields')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['workspace_id', 'status']);
            $table->index(['user_id', 'expense_date']);
            $table->index(['project_id']);
            $table->index(['customer_id']);
            $table->index(['category_id']);
            $table->index(['expense_number']);
            $table->index(['expense_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
