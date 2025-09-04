<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use App\Traits\HasPolicyHelpers;

class InvoicePolicy
{
    use HasPolicyHelpers;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-invoices');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        if (!$user->can('view-invoices')) {
            return false;
        }

        // Admins and managers can view all invoices
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can view invoices they created
        if ($user->hasRole('employee')) {
            return $invoice->created_by === $user->id;
        }

        // Clients can only view their own invoices
        if ($user->hasRole('client')) {
            return $invoice->customer && $invoice->customer->email === $user->email;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-invoices');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Invoice $invoice): bool
    {
        if (!$user->can('update-invoices')) {
            return false;
        }

        // Cannot update paid or cancelled invoices
        if (in_array($invoice->status, ['paid', 'cancelled', 'void'])) {
            return false;
        }

        // Admins and managers can update any invoice
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can only update invoices they created if still draft
        if ($user->hasRole('employee')) {
            return $invoice->created_by === $user->id && $invoice->status === 'draft';
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        if (!$user->can('delete-invoices')) {
            return false;
        }

        // Cannot delete invoices with payments
        if ($invoice->payments()->exists()) {
            return false;
        }

        // Cannot delete sent or paid invoices
        if (in_array($invoice->status, ['sent', 'paid', 'partial'])) {
            return false;
        }

        // Admins and managers can delete eligible invoices
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can only delete their own draft invoices
        return $invoice->created_by === $user->id && $invoice->status === 'draft';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Invoice $invoice): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->can('update-invoices');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Invoice $invoice): bool
    {
        return $user->hasRole('admin') && $user->can('delete-invoices');
    }

    /**
     * Determine whether the user can send the invoice.
     */
    public function send(User $user, Invoice $invoice): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && 
               $invoice->status === 'draft' &&
               $user->can('update-invoices');
    }

    /**
     * Determine whether the user can mark as paid.
     */
    public function markAsPaid(User $user, Invoice $invoice): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && 
               in_array($invoice->status, ['sent', 'overdue', 'partial']) &&
               $user->can('process-payments');
    }

    /**
     * Determine whether the user can void the invoice.
     */
    public function void(User $user, Invoice $invoice): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && 
               !in_array($invoice->status, ['paid', 'void']) &&
               $user->can('update-invoices');
    }
}
