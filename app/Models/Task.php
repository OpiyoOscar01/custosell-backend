<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'assigned_to',
        'created_by',
        'status',
        'priority',
        'start_date',
        'due_date',
        'completed_at',
        'estimated_hours',
        'actual_hours',
        'progress_percentage',
        'tags',
        'is_billable',
        'hourly_rate'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'progress_percentage' => 'integer',
        'is_billable' => 'boolean',
        'tags' => 'array'
    ];

    /**
     * Get the project this task belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user assigned to this task
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created this task
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all time entries for this task
     */
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    /**
     * Scope to get tasks by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get tasks by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope to get overdue tasks
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Scope to get completed tasks
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get active tasks
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Scope to get tasks assigned to a user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date &&
            $this->due_date->isPast() &&
            !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Check if task is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Mark task as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress_percentage' => 100
        ]);

        // Update project progress
        $this->project->updateProgress();
    }

    /**
     * Get total hours logged for this task
     */
    public function getTotalHoursLoggedAttribute()
    {
        return $this->timeEntries()->sum('duration_minutes') / 60;
    }

    /**
     * Get billable hours for this task
     */
    public function getBillableHoursAttribute()
    {
        return $this->timeEntries()
            ->where('is_billable', true)
            ->sum('duration_minutes') / 60;
    }

    /**
     * Get remaining hours for this task
     */
    public function getRemainingHoursAttribute()
    {
        if (!$this->estimated_hours) {
            return null;
        }
        return max(0, $this->estimated_hours - $this->total_hours_logged);
    }

    /**
     * Check if task is over estimated hours
     */
    public function isOverEstimate(): bool
    {
        return $this->estimated_hours &&
            $this->total_hours_logged > $this->estimated_hours;
    }

    /**
     * Calculate task progress based on hours
     */
    public function calculateProgressByHours(): int
    {
        if (!$this->estimated_hours || $this->estimated_hours == 0) {
            return 0;
        }

        $progress = ($this->total_hours_logged / $this->estimated_hours) * 100;
        return min(100, round($progress));
    }

    /**
     * Update task progress
     */
    public function updateProgress($percentage = null): void
    {
        $progress = $percentage ?? $this->calculateProgressByHours();
        $this->update(['progress_percentage' => $progress]);

        // Auto-complete if 100%
        if ($progress >= 100 && $this->status !== 'completed') {
            $this->markAsCompleted();
        }
    }
}
