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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color')->nullable(); // Hex color code
            $table->string('icon')->nullable(); // Icon class or image path
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade'); // For hierarchical categories
            $table->enum('type', ['product', 'expense', 'general'])->default('general');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
            $table->timestamps();

            // Indexes
            $table->index(['workspace_id', 'type', 'is_active']);
            $table->index(['parent_id']);
            $table->index(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
