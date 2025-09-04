<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'user_id',
        'project_id',
        'task_id',
        'invoice_id',
        'description',
        'start_time',
        'end_time',
        'duration_minutes',
        'date',
        'hourly_rate',
        'is_billable',
        'is_invoiced',
        'tags'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'date' => 'date',
        'duration_minutes' => 'integer',
        'hourly_rate' => 'decimal:2',
        'is_billable' => 'boolean',
        'is_invoiced' => 'boolean',
        'tags' => 'array'
    ];

    /**
     * Get the workspace this time entry belongs to
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Get the user who created this time entry
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project for this time entry
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the task for this time entry (optional)
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the invoice this time entry is included in (optional)
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Scope to get billable time entries
     */
    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    /**
     * Scope to get non-billable time entries
     */
    public function scopeNonBillable($query)
    {
        return $query->where('is_billable', false);
    }

    /**
     * Scope to get invoiced time entries
     */
    public function scopeInvoiced($query)
    {
        return $query->where('is_invoiced', true);
    }

    /**
     * Scope to get non-invoiced time entries
     */
    public function scopeNotInvoiced($query)
    {
        return $query->where('is_invoiced', false);
    }

    /**
     * Scope to get time entries by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get time entries by project
     */
    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope to get time entries by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Calculate duration from start and end time
     */
    public function calculateDuration(): void
    {
        if ($this->start_time && $this->end_time) {
            $this->duration_minutes = $this->end_time->diffInMinutes($this->start_time);
        }
    }

    /**
     * Get duration in hours
     */
    public function getDurationHoursAttribute()
    {
        return $this->duration_minutes / 60;
    }

    /**
     * Get total earnings for this time entry
     */
    public function getTotalEarningsAttribute()
    {
        return $this->duration_hours * $this->hourly_rate;
    }

    /**
     * Check if time entry is currently running
     */
    public function isRunning(): bool
    {
        return $this->start_time && !$this->end_time;
    }

    /**
     * Stop the timer
     */
    public function stop(): void
    {
        if ($this->isRunning()) {
            $this->update(['end_time' => now()]);
            $this->calculateDuration();
            $this->save();
        }
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
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($timeEntry) {
            // Auto-calculate duration if start and end times are set
            if ($timeEntry->start_time && $timeEntry->end_time && !$timeEntry->duration_minutes) {
                $timeEntry->calculateDuration();
            }
            
            // Set date from start_time if not provided
            if ($timeEntry->start_time && !$timeEntry->date) {
                $timeEntry->date = $timeEntry->start_time->toDateString();
            }
        });
    }
}
