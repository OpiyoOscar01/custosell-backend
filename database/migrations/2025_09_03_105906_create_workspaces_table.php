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
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->string('industry')->nullable();
            $table->integer('employees_count')->nullable();
            $table->decimal('monthly_budget', 15, 2)->nullable();
            $table->json('goals')->nullable(); // Store as JSON array
            $table->json('features')->nullable(); // Store as JSON array
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspaces');
    }
};
