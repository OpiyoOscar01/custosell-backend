<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\Traits\HasPolicyHelpers;

class OrderPolicy
{
    use HasPolicyHelpers;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-orders');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        if (!$user->can('view-orders')) {
            return false;
        }

        // Admins and managers can view all orders
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can view orders they created
        if ($user->hasRole('employee')) {
            return $order->created_by === $user->id;
        }

        // Clients can only view their own orders
        if ($user->hasRole('client')) {
            return $order->customer && $order->customer->email === $user->email;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-orders');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        if (!$user->can('update-orders')) {
            return false;
        }

        // Cannot update shipped orders
        if ($order->status === 'shipped' || $order->status === 'delivered') {
            return false;
        }

        // Admins and managers can update any order
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can only update orders they created and are still pending
        if ($user->hasRole('employee')) {
            return $order->created_by === $user->id && $order->status === 'pending';
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        if (!$user->can('delete-orders')) {
            return false;
        }

        // Cannot delete orders that have been processed
        if (in_array($order->status, ['shipped', 'delivered', 'paid'])) {
            return false;
        }

        // Cannot delete if there are related invoices
        if ($order->invoices()->exists()) {
            return false;
        }

        // Admins and managers can delete eligible orders
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can only delete their own pending orders
        return $order->created_by === $user->id && $order->status === 'pending';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->can('update-orders');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return $user->hasRole('admin') && $user->can('delete-orders');
    }

    /**
     * Determine whether the user can ship the order.
     */
    public function ship(User $user, Order $order): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) &&
            $order->status === 'confirmed' &&
            $user->can('update-orders');
    }

    /**
     * Determine whether the user can confirm the order.
     */
    public function confirm(User $user, Order $order): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) &&
            $order->status === 'pending' &&
            $user->can('update-orders');
    }
}
