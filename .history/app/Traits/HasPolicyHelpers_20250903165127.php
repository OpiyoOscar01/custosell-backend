<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Policy helper trait providing common authorization patterns
 * 
 * This trait assumes the User model uses Spatie\Permission\Traits\HasRoles
 * and has the following methods available:
 * - hasRole(string $role): bool
 * - hasAnyRole(array $roles): bool  
 * - can(string $permission): bool
 */
trait HasPolicyHelpers
{
    /**
     * Authorize an action and return JSON response on failure
     */
    protected function authorizeWithResponse(string $ability, $arguments = []): bool
    {
        try {
            $this->authorize($ability, $arguments);
            return true;
        } catch (AuthorizationException $e) {
            return false;
        }
    }

    /**
     * Return a standardized authorization failure response
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized action'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => 'You do not have permission to perform this action'
        ], 403);
    }

    /**
     * Check if user can perform bulk actions
     */
    protected function canPerformBulkActions(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user?->can('bulk-actions') ?? false;
    }

    /**
     * Check if user is admin or manager
     */
    protected function isAdminOrManager(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user?->hasAnyRole(['admin', 'manager']) ?? false;
    }

    /**
     * Check if user owns the resource
     */
    protected function userOwnsResource($resource, string $userField = 'user_id'): bool
    {
        $userId = Auth::id();
        return $userId && isset($resource->{$userField}) && $resource->{$userField} === $userId;
    }

    /**
     * Check if user created the resource
     */
    protected function userCreatedResource($resource, string $createdByField = 'created_by'): bool
    {
        $userId = Auth::id();
        return $userId && isset($resource->{$createdByField}) && $resource->{$createdByField} === $userId;
    }

    /**
     * Check if resource can be modified based on status
     */
    protected function canModifyByStatus($resource, array $allowedStatuses): bool
    {
        if (!isset($resource->status)) {
            return true; // No status field means it's modifiable
        }

        return in_array($resource->status, $allowedStatuses);
    }

    /**
     * Check if resource has related records that prevent deletion
     */
    protected function hasBlockingRelations($resource, array $relations): bool
    {
        foreach ($relations as $relation) {
            if (method_exists($resource, $relation) && $resource->{$relation}()->exists()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has workspace access
     */
    protected function hasWorkspaceAccess($resource, string $workspaceField = 'workspace_id'): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user || !isset($resource->{$workspaceField})) {
            return false;
        }

        // Admins have access to all workspaces
        if ($user->hasRole('admin')) {
            return true;
        }

        // Check if user is member of the workspace (if method exists)
        if (method_exists($user, 'workspaces')) {
            return $user->workspaces()->where('workspace_id', $resource->{$workspaceField})->exists();
        }

        return false;
    }

    /**
     * Get filtered results based on user permissions
     */
    protected function getFilteredResults($query, string $userField = 'user_id')
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return $query->whereRaw('1 = 0'); // Return empty result for safety
        }

        // Admins and managers see everything
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return $query;
        }

        // Employees see only their own records
        if ($user->hasRole('employee')) {
            return $query->where($userField, $user->id);
        }

        // Clients see records related to them
        if ($user->hasRole('client')) {
            // This logic would need to be customized per entity
            return $query->whereHas('customer', function ($q) use ($user) {
                $q->where('email', $user->email);
            });
        }

        return $query->whereRaw('1 = 0'); // Return empty result
    }

    /**
     * Common permission check with role and permission validation
     */
    protected function hasPermissionAndRole(string $permission, array $allowedRoles = ['admin', 'manager']): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        return $user->can($permission) && $user->hasAnyRole($allowedRoles);
    }

    /**
     * Check if user can view resource based on ownership and roles
     */
    protected function canViewResource($resource, string $ownerField = 'created_by'): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Admins and managers can view all
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Check ownership
        if ($this->userCreatedResource($resource, $ownerField)) {
            return true;
        }

        // Additional custom logic can be added in specific policies
        return false;
    }

    /**
     * Check if user can modify resource based on ownership, roles, and status
     */
    protected function canModifyResource($resource, array $allowedStatuses = [], string $ownerField = 'created_by'): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Check status restrictions
        if (!empty($allowedStatuses) && !$this->canModifyByStatus($resource, $allowedStatuses)) {
            return false;
        }

        // Admins and managers can modify most resources
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Check ownership for employees
        if ($user->hasRole('employee')) {
            return $this->userCreatedResource($resource, $ownerField);
        }

        return false;
    }

    /**
     * Check if user can delete resource based on relationships and ownership
     */
    protected function canDeleteResource($resource, array $blockingRelations = [], string $ownerField = 'created_by'): bool
    {
        // Check for blocking relationships
        if ($this->hasBlockingRelations($resource, $blockingRelations)) {
            return false;
        }

        return $this->canModifyResource($resource, [], $ownerField);
    }

    /**
     * Safe method to check if user has a specific role
     */
    protected function userHasRole(string $role): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user?->hasRole($role) ?? false;
    }

    /**
     * Safe method to check if user has any of the specified roles
     */
    protected function userHasAnyRole(array $roles): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user?->hasAnyRole($roles) ?? false;
    }

    /**
     * Safe method to check if user has a specific permission
     */
    protected function userCan(string $permission): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user?->can($permission) ?? false;
    }
}