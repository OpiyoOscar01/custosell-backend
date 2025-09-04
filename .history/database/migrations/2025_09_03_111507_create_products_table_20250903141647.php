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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique(); // Stock Keeping Unit
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->enum('type', ['product', 'service', 'digital'])->default('product');
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable(); // Cost to acquire/produce
            $table->decimal('sale_price', 10, 2)->nullable(); // Override price for sales
            $table->string('unit')->default('piece'); // unit, kg, hour, etc.
            $table->integer('stock_quantity')->default(0);
            $table->integer('minimum_stock')->default(0); // Reorder level
            $table->boolean('track_stock')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->json('images')->nullable(); // Array of image URLs
            $table->json('attributes')->nullable(); // Custom attributes (size, color, etc.)
            $table->string('barcode')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('dimensions')->nullable(); // length, width, height
            $table->decimal('tax_rate', 5, 2)->default(0); // Tax percentage
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            // Indexes
            $table->index(['workspace_id', 'is_active']);
            $table->index(['category_id']);
            $table->index(['sku']);
            $table->index(['type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
