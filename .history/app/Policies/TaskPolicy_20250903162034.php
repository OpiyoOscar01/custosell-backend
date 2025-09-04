<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-tasks');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        if ($user->can('view-tasks')) {
            // Employees can view tasks assigned to them or in their projects
            if ($user->hasRole('employee')) {
                return $task->assigned_to === $user->id ||
                    $task->project->team_members()->where('user_id', $user->id)->exists();
            }

            // Clients can view tasks in their projects
            if ($user->hasRole('client')) {
                return $task->project->customer->email === $user->email;
            }

            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-tasks');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        if (!$user->can('update-tasks')) {
            return false;
        }

        // Admins and managers can update any task
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can update tasks assigned to them
        if ($user->hasRole('employee')) {
            return $task->assigned_to === $user->id;
        }

        // Clients cannot update tasks directly
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        if (!$user->can('delete-tasks')) {
            return false;
        }

        // Prevent deletion if task has time entries
        if ($task->timeEntries()->exists()) {
            return false;
        }

        // Only admins and managers can delete tasks
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can assign the task.
     */
    public function assign(User $user, Task $task): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->can('assign-tasks');
    }

    /**
     * Determine whether the user can change task status.
     */
    public function changeStatus(User $user, Task $task): bool
    {
        if (!$user->can('update-tasks')) {
            return false;
        }

        // Admins and managers can change any task status
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can change status of assigned tasks
        if ($user->hasRole('employee')) {
            return $task->assigned_to === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can view task time entries.
     */
    public function viewTimeEntries(User $user, Task $task): bool
    {
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can view time entries for their tasks
        if ($user->hasRole('employee')) {
            return $task->assigned_to === $user->id;
        }

        return false;
    }
}
