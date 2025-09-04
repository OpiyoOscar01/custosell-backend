<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_number',
        'workspace_id',
        'user_id',
        'category_id',
        'project_id',
        'customer_id',
        'invoice_id',
        'title',
        'description',
        'amount',
        'currency',
        'expense_date',
        'payment_method',
        'vendor',
        'receipt_number',
        'status',
        'is_billable',
        'is_reimbursable',
        'tax_amount',
        'total_amount',
        'attachments',
        'notes',
        'approved_by',
        'approved_at',
        'custom_fields'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'approved_at' => 'datetime',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_billable' => 'boolean',
        'is_reimbursable' => 'boolean',
        'attachments' => 'array',
        'custom_fields' => 'array'
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
     * Get the category for this expense (optional)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the customer for this expense (optional)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who approved this expense (optional)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
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
