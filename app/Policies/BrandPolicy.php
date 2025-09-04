<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use App\Traits\HasPolicyHelpers;

class BrandPolicy
{
    use HasPolicyHelpers;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-brands');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Brand $brand): bool
    {
        // Users can view brands if they have permission
        if ($user->can('view-brands')) {
            return $this->canViewResource($brand);
        }

        // Check if user belongs to this brand's company
        return $user->currentWorkspace &&
            $user->currentWorkspace->companies->contains($brand->company);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-brands');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Brand $brand): bool
    {
        if (!$user->can('update-brands')) {
            return false;
        }

        return $this->canModifyResource($brand);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Brand $brand): bool
    {
        if (!$user->can('delete-brands')) {
            return false;
        }

        // Check for blocking relationships (products)
        return $this->canDeleteResource($brand, ['products']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Brand $brand): bool
    {
        return $this->hasPermissionAndRole('update-brands');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Brand $brand): bool
    {
        return $user->hasRole('admin') && $user->can('delete-brands');
    }
}
