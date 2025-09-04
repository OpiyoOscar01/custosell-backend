<?php

namespace App\Policies;

use App\Models\Workspace;
use App\Models\User;
use App\Traits\HasPolicyHelpers;

class WorkspacePolicy
{
    use HasPolicyHelpers;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Users can only see workspaces they belong to
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Workspace $workspace): bool
    {
        // Owner can always view
        if ($workspace->owner_id === $user->id) {
            return true;
        }

        // Members can view workspace
        return $workspace->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create workspaces
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Workspace $workspace): bool
    {
        // Only owner can update workspace settings
        if ($workspace->owner_id === $user->id) {
            return true;
        }

        // Admins in the workspace can update certain fields
        return $workspace->users()
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Workspace $workspace): bool
    {
        // Only the owner can delete the workspace
        return $workspace->owner_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Workspace $workspace): bool
    {
        return $workspace->owner_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Workspace $workspace): bool
    {
        return $workspace->owner_id === $user->id;
    }

    /**
     * Determine whether the user can manage workspace members.
     */
    public function manageMembers(User $user, Workspace $workspace): bool
    {
        // Owner can always manage members
        if ($workspace->owner_id === $user->id) {
            return true;
        }

        // Admins in the workspace can manage members
        return $workspace->users()
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }

    /**
     * Determine whether the user can invite users to workspace.
     */
    public function invite(User $user, Workspace $workspace): bool
    {
        return $this->manageMembers($user, $workspace);
    }

    /**
     * Determine whether the user can remove users from workspace.
     */
    public function removeMember(User $user, Workspace $workspace): bool
    {
        return $this->manageMembers($user, $workspace);
    }

    /**
     * Determine whether the user can change member roles.
     */
    public function changeRole(User $user, Workspace $workspace): bool
    {
        return $this->manageMembers($user, $workspace);
    }

    /**
     * Determine whether the user can access workspace settings.
     */
    public function manageSettings(User $user, Workspace $workspace): bool
    {
        return $workspace->owner_id === $user->id || $user->can('manage-settings');
    }
}
