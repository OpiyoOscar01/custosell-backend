<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-expenses');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Expense $expense): bool
    {
        if ($user->can('view-expenses')) {
            // Admins and managers can view all expenses
            if ($user->hasAnyRole(['admin', 'manager'])) {
                return true;
            }

            // Employees can only view their own expenses
            if ($user->hasRole('employee')) {
                return $expense->user_id === $user->id;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create-expenses');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Expense $expense): bool
    {
        if (!$user->can('update-expenses')) {
            return false;
        }

        // Cannot update approved expenses
        if ($expense->is_approved) {
            return $user->hasRole('admin');
        }

        // Admins and managers can update any expense
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can only update their own expenses within 7 days
        if ($user->hasRole('employee') && $expense->user_id === $user->id) {
            return $expense->created_at->diffInDays(now()) <= 7;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Expense $expense): bool
    {
        if (!$user->can('delete-expenses')) {
            return false;
        }

        // Cannot delete approved expenses
        if ($expense->is_approved) {
            return $user->hasRole('admin');
        }

        // Admins and managers can delete any expense
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // Employees can only delete their own expenses within 7 days
        if ($user->hasRole('employee') && $expense->user_id === $user->id) {
            return $expense->created_at->diffInDays(now()) <= 7;
        }

        return false;
    }

    /**
     * Determine whether the user can approve the expense.
     */
    public function approve(User $user, Expense $expense): bool
    {
        // Only users with approve-expenses permission can approve
        if (!$user->can('approve-expenses')) {
            return false;
        }

        // Cannot approve already approved expenses
        if ($expense->is_approved) {
            return false;
        }

        // Users cannot approve their own expenses
        if ($expense->user_id === $user->id) {
            return false;
        }

        // Check expense amount limits for managers
        if ($user->hasRole('manager') && $expense->amount > 1000) {
            return false; // Managers can only approve expenses up to $1000
        }

        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can reject the expense.
     */
    public function reject(User $user, Expense $expense): bool
    {
        return $this->approve($user, $expense);
    }

    /**
     * Determine whether the user can reimburse the expense.
     */
    public function reimburse(User $user, Expense $expense): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && 
               $expense->is_approved && 
               !$expense->is_reimbursed &&
               $user->can('process-payments');
    }

    /**
     * Determine whether the user can view expense reports.
     */
    public function viewReports(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']) && $user->can('view-reports');
    }
}
