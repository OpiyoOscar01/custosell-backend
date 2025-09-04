<?php

namespace App\Policies;

use App\Models\OrderItem;
use App\Models\User;
use App\Traits\HasPolicyHelpers;

class OrderItemPolicy
{
    use HasPolicyHelpers;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-order-items');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrderItem $orderItem): bool
    {
        if (!$user->can('view-order-items')) {
            return false;
        }

        // Use order policy to determine access
        return $user->can('view', $orderItem->order);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-order-items');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrderItem $orderItem): bool
    {
        if (!$user->can('update-order-items')) {
            return false;
        }

        // Cannot update items in processed orders
        if (in_array($orderItem->order->status, ['shipped', 'delivered', 'paid'])) {
            return false;
        }

        // Use order policy to determine access
        return $user->can('update', $orderItem->order);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrderItem $orderItem): bool
    {
        if (!$user->can('delete-order-items')) {
            return false;
        }

        // Cannot delete items from processed orders
        if (in_array($orderItem->order->status, ['shipped', 'delivered', 'paid'])) {
            return false;
        }

        // Use order policy to determine access
        return $user->can('update', $orderItem->order);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OrderItem $orderItem): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->can('update-order-items');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OrderItem $orderItem): bool
    {
        return $user->hasRole('admin') && $user->can('delete-order-items');
    }
}
