<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use App\Traits\HasPolicyHelpers;

class TeamPolicy
{
    use HasPolicyHelpers;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view teams (for assignment purposes)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Team $team): bool
    {
        // Admins and managers can view all teams
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Team leaders can view their teams
        if ($team->leader_id === $user->id) {
            return true;
        }

        // Team members can view their teams
        if ($team->members()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        // Admins and managers can update any team
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Team leaders can update their teams (limited fields)
        return $team->leader_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        // Only admins and managers can delete teams
        if (!$user->hasAnyRole(['admin', 'manager'])) {
            return false;
        }

        // Cannot delete teams with active projects
        if ($team->projects()->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Team $team): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Team $team): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage team members.
     */
    public function manageMembers(User $user, Team $team): bool
    {
        // Admins and managers can manage any team
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Team leaders can manage their team members
        return $team->leader_id === $user->id;
    }

    /**
     * Determine whether the user can assign team leader.
     */
    public function assignLeader(User $user, Team $team): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }
}
