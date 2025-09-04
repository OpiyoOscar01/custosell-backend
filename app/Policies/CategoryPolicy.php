<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use App\Traits\HasPolicyHelpers;

class CategoryPolicy
{
    use HasPolicyHelpers;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-categories');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): bool
    {
        // Users can view categories if they have permission
        if ($user->can('view-categories')) {
            return $this->canViewResource($category);
        }

        // Clients can only view categories related to their projects
        if ($user->hasRole('client')) {
            // Check if user has projects that use this category
            return $user->projects()
                ->whereHas('tasks', function ($query) use ($category) {
                    $query->where('category_id', $category->id);
                })
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-categories');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        if (!$user->can('update-categories')) {
            return false;
        }

        return $this->canModifyResource($category);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        if (!$user->can('delete-categories')) {
            return false;
        }

        // Check for blocking relationships
        return $this->canDeleteResource($category, ['products', 'expenses']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        return $this->hasPermissionAndRole('update-categories');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return $user->hasRole('admin') && $user->can('delete-categories');
    }
}
