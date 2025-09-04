<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-customers');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Customer $customer): bool
    {
        if ($user->can('view-customers')) {
            return true;
        }

        // Clients can only view their own customer record
        if ($user->hasRole('client')) {
            return $customer->email === $user->email;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-customers');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Customer $customer): bool
    {
        if (!$user->can('update-customers')) {
            return false;
        }

        // Admins and managers can update any customer
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can only update customers they manage
        if ($user->hasRole('employee')) {
            return $customer->assigned_to === $user->id;
        }

        // Clients can update their own basic information
        if ($user->hasRole('client')) {
            return $customer->email === $user->email;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Customer $customer): bool
    {
        if (!$user->can('delete-customers')) {
            return false;
        }

        // Prevent deletion if customer has active projects or orders
        if ($customer->projects()->exists() || $customer->orders()->exists()) {
            return false;
        }

        // Only admins and managers can delete customers
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Customer $customer): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->can('update-customers');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Customer $customer): bool
    {
        return $user->hasRole('admin') && $user->can('delete-customers');
    }

    /**
     * Determine whether the user can assign the customer to employees.
     */
    public function assign(User $user, Customer $customer): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->can('update-customers');
    }
}
