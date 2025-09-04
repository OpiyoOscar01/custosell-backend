<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'user_id',
        'project_id',
        'invoice_id',
        'category',
        'description',
        'amount',
        'currency',
        'expense_date',
        'payment_method',
        'receipt_file',
        'is_billable',
        'is_invoiced',
        'vendor',
        'reference_number',
        'notes',
        'tags'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
        'is_billable' => 'boolean',
        'is_invoiced' => 'boolean',
        'tags' => 'array'
    ];

    /**
     * Get the workspace this expense belongs to
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Get the user who created this expense
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project for this expense (optional)
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the invoice this expense is included in (optional)
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Scope to get billable expenses
     */
    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    /**
     * Scope to get non-billable expenses
     */
    public function scopeNonBillable($query)
    {
        return $query->where('is_billable', false);
    }

    /**
     * Scope to get invoiced expenses
     */
    public function scopeInvoiced($query)
    {
        return $query->where('is_invoiced', true);
    }

    /**
     * Scope to get non-invoiced expenses
     */
    public function scopeNotInvoiced($query)
    {
        return $query->where('is_invoiced', false);
    }

    /**
     * Scope to get expenses by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get expenses by project
     */
    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope to get expenses by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get expenses by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }

    /**
     * Mark as invoiced
     */
    public function markAsInvoiced($invoiceId = null): void
    {
        $this->update([
            'is_invoiced' => true,
            'invoice_id' => $invoiceId
        ]);
    }

    /**
     * Mark as not invoiced
     */
    public function markAsNotInvoiced(): void
    {
        $this->update([
            'is_invoiced' => false,
            'invoice_id' => null
        ]);
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        return ($this->currency ?? 'USD') . ' ' . number_format($this->amount, 2);
    }

    /**
     * Check if expense has receipt
     */
    public function hasReceipt(): bool
    {
        return !empty($this->receipt_file);
    }

    /**
     * Get receipt URL
     */
    public function getReceiptUrlAttribute()
    {
        if (!$this->receipt_file) {
            return null;
        }

        return asset('storage/' . $this->receipt_file);
    }

    /**
     * Get common expense categories
     */
    public static function getCommonCategories(): array
    {
        return [
            'Travel',
            'Meals & Entertainment',
            'Office Supplies',
            'Software & Tools',
            'Marketing',
            'Training & Education',
            'Equipment',
            'Professional Services',
            'Utilities',
            'Other'
        ];
    }
}
