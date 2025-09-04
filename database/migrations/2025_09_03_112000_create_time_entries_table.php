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
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('project_id')->nullable()->constrained('projects');
            $table->foreignId('task_id')->nullable()->constrained('tasks');
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time')->nullable();
            $table->integer('duration_minutes')->nullable(); // Calculated or manual entry
            $table->boolean('is_billable')->default(true);
            $table->decimal('hourly_rate', 8, 2)->nullable(); // Rate at time of entry
            $table->decimal('total_amount', 10, 2)->default(0); // duration * rate
            $table->enum('status', ['draft', 'submitted', 'approved', 'invoiced'])->default('draft');
            $table->date('date'); // Date of work performed
            $table->json('tags')->nullable();
            $table->boolean('is_manual')->default(false); // Manual entry vs timer
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->datetime('approved_at')->nullable();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices'); // If invoiced
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'date']);
            $table->index(['project_id', 'date']);
            $table->index(['task_id']);
            $table->index(['workspace_id', 'status']);
            $table->index(['invoice_id']);
            $table->index(['start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};
