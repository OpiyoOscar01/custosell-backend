<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;
use App\Traits\HasPolicyHelpers;

class BranchPolicy
{
    use HasPolicyHelpers;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-branches');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Branch $branch): bool
    {
        // Users can view branches if they have permission
        if ($user->can('view-branches')) {
            return $this->canViewResource($branch);
        }

        // Check if user belongs to this branch's company
        return $user->currentWorkspace &&
            $user->currentWorkspace->companies->contains($branch->company);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-branches');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Branch $branch): bool
    {
        if (!$user->can('update-branches')) {
            return false;
        }

        return $this->canModifyResource($branch);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Branch $branch): bool
    {
        if (!$user->can('delete-branches')) {
            return false;
        }

        // Check for blocking relationships (products, orders, etc.)
        return $this->canDeleteResource($branch, ['products', 'orders', 'inventories']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Branch $branch): bool
    {
        return $this->hasPermissionAndRole('update-branches');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Branch $branch): bool
    {
        return $user->hasRole('admin') && $user->can('delete-branches');
    }
}
