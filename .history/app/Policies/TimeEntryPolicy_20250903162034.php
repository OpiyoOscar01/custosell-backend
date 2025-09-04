<?php

namespace App\Policies;

use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimeEntryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-time-entries');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TimeEntry $timeEntry): bool
    {
        if ($user->can('view-time-entries')) {
            // Admins and managers can view all time entries
            if ($user->hasAnyRole(['admin', 'manager'])) {
                return true;
            }

            // Employees can only view their own time entries
            if ($user->hasRole('employee')) {
                return $timeEntry->user_id === $user->id;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-time-entries');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TimeEntry $timeEntry): bool
    {
        if (!$user->can('update-time-entries')) {
            return false;
        }

        // Cannot update approved time entries
        if ($timeEntry->is_approved) {
            return $user->hasRole('admin');
        }

        // Admins and managers can update any time entry
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can only update their own time entries within 24 hours
        if ($user->hasRole('employee') && $timeEntry->user_id === $user->id) {
            return $timeEntry->created_at->diffInHours(now()) <= 24;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TimeEntry $timeEntry): bool
    {
        if (!$user->can('delete-time-entries')) {
            return false;
        }

        // Cannot delete approved time entries
        if ($timeEntry->is_approved) {
            return $user->hasRole('admin');
        }

        // Admins and managers can delete any time entry
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can only delete their own time entries within 24 hours
        if ($user->hasRole('employee') && $timeEntry->user_id === $user->id) {
            return $timeEntry->created_at->diffInHours(now()) <= 24;
        }

        return false;
    }

    /**
     * Determine whether the user can approve the time entry.
     */
    public function approve(User $user, TimeEntry $timeEntry): bool
    {
        // Only managers and admins can approve time entries
        if (!$user->hasAnyRole(['admin', 'manager'])) {
            return false;
        }

        // Cannot approve already approved entries
        if ($timeEntry->is_approved) {
            return false;
        }

        // Managers cannot approve their own time entries
        if ($timeEntry->user_id === $user->id) {
            return $user->hasRole('admin');
        }

        return true;
    }

    /**
     * Determine whether the user can start a timer.
     */
    public function startTimer(User $user): bool
    {
        return $user->can('create-time-entries') && $user->hasAnyRole(['employee', 'manager', 'admin']);
    }

    /**
     * Determine whether the user can stop a timer.
     */
    public function stopTimer(User $user, TimeEntry $timeEntry): bool
    {
        return $timeEntry->user_id === $user->id && is_null($timeEntry->end_time);
    }

    /**
     * Determine whether the user can view time reports.
     */
    public function viewReports(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->can('view-reports');
    }
}
