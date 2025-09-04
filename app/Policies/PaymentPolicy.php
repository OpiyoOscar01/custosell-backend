<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use App\Traits\HasPolicyHelpers;

class PaymentPolicy
{
    use HasPolicyHelpers;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-payments');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payment $payment): bool
    {
        if (!$user->can('view-payments')) {
            return false;
        }

        // Admins and managers can view all payments
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can view payments they processed
        if ($user->hasRole('employee')) {
            return $payment->created_by === $user->id;
        }

        // Clients can only view their own payments
        if ($user->hasRole('client')) {
            return $payment->customer && $payment->customer->email === $user->email;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-payments') && $user->can('process-payments');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment): bool
    {
        if (!$user->can('update-payments')) {
            return false;
        }

        // Cannot update processed payments
        if (in_array($payment->status, ['completed', 'failed', 'refunded'])) {
            return false;
        }

        // Only admins and managers can update payments
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $payment): bool
    {
        if (!$user->can('delete-payments')) {
            return false;
        }

        // Cannot delete completed payments
        if ($payment->status === 'completed') {
            return false;
        }

        // Only admins can delete payments
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Payment $payment): bool
    {
        return $user->hasRole('admin') && $user->can('update-payments');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Payment $payment): bool
    {
        return $user->hasRole('admin') && $user->can('delete-payments');
    }

    /**
     * Determine whether the user can process the payment.
     */
    public function process(User $user, Payment $payment): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) &&
            $payment->status === 'pending' &&
            $user->can('process-payments');
    }

    /**
     * Determine whether the user can refund the payment.
     */
    public function refund(User $user, Payment $payment): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) &&
            $payment->status === 'completed' &&
            $user->can('process-payments');
    }

    /**
     * Determine whether the user can void the payment.
     */
    public function void(User $user, Payment $payment): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) &&
            in_array($payment->status, ['pending', 'processing']) &&
            $user->can('process-payments');
    }
}
