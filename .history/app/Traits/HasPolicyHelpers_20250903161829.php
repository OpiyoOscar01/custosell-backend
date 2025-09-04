<?php

namespace App\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
        return auth()->user()?->can('bulk-actions') ?? false;
    }

    /**
     * Check if user is admin or manager
     */
    protected function isAdminOrManager(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'manager']) ?? false;
    }

    /**
     * Check if user owns the resource
     */
    protected function userOwnsResource($resource, string $userField = 'user_id'): bool
    {
        $userId = auth()->id();
        return $userId && $resource->{$userField} === $userId;
    }

    /**
     * Get filtered results based on user permissions
     */
    protected function getFilteredResults($query, string $userField = 'user_id')
    {
        $user = auth()->user();

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
}
