<?php

namespace App\Policies;

use App\Models\Unit;
use App\Models\User;
use App\Traits\HasPolicyHelpers;

class UnitPolicy
{
    use HasPolicyHelpers;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-units');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Unit $unit): bool
    {
        // Users can view units if they have permission
        if ($user->can('view-units')) {
            return $this->canViewResource($unit);
        }

        // Check if user belongs to the workspace
        return $user->currentWorkspace && $unit->workspace_id === $user->currentWorkspace->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-units');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Unit $unit): bool
    {
        if (!$user->can('update-units')) {
            return false;
        }

        return $this->canModifyResource($unit);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Unit $unit): bool
    {
        if (!$user->can('delete-units')) {
            return false;
        }

        // Check for blocking relationships (products, child units)
        return $this->canDeleteResource($unit, ['products', 'childUnits']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Unit $unit): bool
    {
        return $this->hasPermissionAndRole('update-units');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Unit $unit): bool
    {
        return $user->hasRole('admin') && $user->can('delete-units');
    }
}
