<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use App\Traits\HasPolicyHelpers;

class CompanyPolicy
{
    use HasPolicyHelpers;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-companies');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Company $company): bool
    {
        // Users can view companies if they have permission
        if ($user->can('view-companies')) {
            return $this->canViewResource($company);
        }

        // Check if user belongs to this company
        return $user->currentWorkspace && $user->currentWorkspace->companies->contains($company);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-companies');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): bool
    {
        if (!$user->can('update-companies')) {
            return false;
        }

        return $this->canModifyResource($company);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Company $company): bool
    {
        if (!$user->can('delete-companies')) {
            return false;
        }

        // Check for blocking relationships (branches, brands, etc.)
        return $this->canDeleteResource($company, ['branches', 'brands', 'products']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Company $company): bool
    {
        return $this->hasPermissionAndRole('update-companies');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Company $company): bool
    {
        return $user->hasRole('admin') && $user->can('delete-companies');
    }
}
