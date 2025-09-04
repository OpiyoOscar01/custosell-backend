<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
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
            return true;
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

        // Admins and managers can update any category
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can only update categories they created (if they have the permission)
        return $category->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        if (!$user->can('delete-categories')) {
            return false;
        }

        // Prevent deletion if category has related records
        if ($category->products()->exists() || $category->expenses()->exists()) {
            return false;
        }

        // Admins and managers can delete any category
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can only delete categories they created
        return $category->created_by === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->can('update-categories');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return $user->hasRole('admin') && $user->can('delete-categories');
    }
}
