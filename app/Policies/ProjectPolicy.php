<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-projects');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->can('view-projects')) {
            // Admins and managers can view all projects
            if ($user->hasAnyRole(['admin', 'manager'])) {
                return true;
            }

            // Employees can view projects they are assigned to
            if ($user->hasRole('employee')) {
                return $project->team_members()->where('user_id', $user->id)->exists() ||
                    $project->tasks()->where('assigned_to', $user->id)->exists();
            }

            // Clients can view their own projects
            if ($user->hasRole('client')) {
                return $project->customer->email === $user->email;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-projects');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        if (!$user->can('update-projects')) {
            return false;
        }

        // Admins and managers can update any project
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Project leaders can update their projects
        if ($user->hasRole('employee')) {
            return $project->project_manager_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        if (!$user->can('delete-projects')) {
            return false;
        }

        // Cannot delete projects with completed tasks or time entries
        if (
            $project->tasks()->whereIn('status', ['completed', 'in_progress'])->exists() ||
            $project->timeEntries()->exists()
        ) {
            return false;
        }

        // Only admins and managers can delete projects
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can assign team members.
     */
    public function assignMembers(User $user, Project $project): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->can('assign-tasks');
    }

    /**
     * Determine whether the user can change project status.
     */
    public function changeStatus(User $user, Project $project): bool
    {
        if (!$user->can('update-projects')) {
            return false;
        }

        // Admins and managers can change any project status
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Project managers can change their project status
        return $project->project_manager_id === $user->id;
    }

    /**
     * Determine whether the user can view project financials.
     */
    public function viewFinancials(User $user, Project $project): bool
    {
        if (!$user->hasAnyRole(['admin', 'manager'])) {
            return false;
        }

        return $user->can('view-reports');
    }

    /**
     * Determine whether the user can create invoices for the project.
     */
    public function createInvoice(User $user, Project $project): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) &&
            $user->can('create-invoices') &&
            $project->status !== 'draft';
    }
}
