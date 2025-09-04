<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'contact_person',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'website',
        'tax_number',
        'type',
        'status',
        'notes',
        'custom_fields',
        'credit_limit',
        'outstanding_balance',
        'workspace_id',
        'created_by'
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'credit_limit' => 'decimal:2',
        'outstanding_balance' => 'decimal:2'
    ];

    /**
     * Get the workspace this customer belongs to
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Get the user who created this customer
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all projects for this customer
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get all orders for this customer
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all invoices for this customer
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get all payments from this customer
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all expenses related to this customer
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Scope to get only active customers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get customers by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get customer's full name
     */
    public function getFullNameAttribute()
    {
        if ($this->type === 'business') {
            return $this->company_name ?: $this->name;
        }
        return $this->name;
    }

    /**
     * Get customer's display name with company
     */
    public function getDisplayNameAttribute()
    {
        if ($this->type === 'business' && $this->company_name) {
            return $this->contact_person ? 
                "{$this->contact_person} ({$this->company_name})" : 
                $this->company_name;
        }
        return $this->name;
    }

    /**
     * Check if customer has outstanding balance
     */
    public function hasOutstandingBalance(): bool
    {
        return $this->outstanding_balance > 0;
    }

    /**
     * Check if customer is over credit limit
     */
    public function isOverCreditLimit(): bool
    {
        if (!$this->credit_limit) {
            return false;
        }
        return $this->outstanding_balance > $this->credit_limit;
    }
}
