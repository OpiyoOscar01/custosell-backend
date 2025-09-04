<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'manager_id',
        'name',
        'code',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'operating_hours',
        'is_warehouse',
        'is_pos_enabled',
        'is_active'
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'is_warehouse' => 'boolean',
        'is_pos_enabled' => 'boolean',
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /**
     * Get the company this branch belongs to
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the manager of this branch
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Scope for active branches
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for warehouses
     */
    public function scopeWarehouses($query)
    {
        return $query->where('is_warehouse', true);
    }

    /**
     * Scope for POS enabled branches
     */
    public function scopePosEnabled($query)
    {
        return $query->where('is_pos_enabled', true);
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);

        return implode(', ', $parts);
    }
}space App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    //
}
