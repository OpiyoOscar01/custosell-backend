<?php

namespace App\Services;

use App\Models\Expense;
use App\Interfaces\ExpenseInterface;
use Illuminate\Database\Eloquent\Collection;

class ExpenseService
{
    protected $expenseRepository;

    public function __construct(ExpenseInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function getAllExpenses(int $workspaceId): Collection
    {
        return $this->expenseRepository->getAllExpenses($workspaceId);
    }

    public function getExpenseById(int $id): ?Expense
    {
        return $this->expenseRepository->getExpenseById($id);
    }

    public function createExpense(array $data): Expense
    {
        // Generate expense number if not provided
        if (!isset($data['expense_number'])) {
            $data['expense_number'] = $this->generateExpenseNumber($data['workspace_id']);
        }

        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        // Calculate total amount if not provided
        if (!isset($data['total_amount'])) {
            $amount = $data['amount'] ?? 0;
            $taxAmount = $data['tax_amount'] ?? 0;
            $data['total_amount'] = $amount + $taxAmount;
        }

        return $this->expenseRepository->createExpense($data);
    }

    public function updateExpense(int $id, array $data): bool
    {
        // Recalculate total amount if components change
        if (isset($data['amount']) || isset($data['tax_amount'])) {
            $expense = $this->getExpenseById($id);
            if ($expense) {
                $amount = $data['amount'] ?? $expense->amount;
                $taxAmount = $data['tax_amount'] ?? $expense->tax_amount;
                $data['total_amount'] = $amount + $taxAmount;
            }
        }

        return $this->expenseRepository->updateExpense($id, $data);
    }

    public function deleteExpense(int $id): bool
    {
        $expense = $this->getExpenseById($id);
        if (!$expense) {
            return false;
        }

        // Check if expense is approved or paid
        if (in_array($expense->status, ['approved', 'paid'])) {
            throw new \Exception('Cannot delete approved or paid expense');
        }

        return $this->expenseRepository->deleteExpense($id);
    }

    public function getExpensesByCategory(int $categoryId): Collection
    {
        return $this->expenseRepository->getExpensesByCategory($categoryId);
    }

    public function getExpensesByProject(int $projectId): Collection
    {
        return $this->expenseRepository->getExpensesByProject($projectId);
    }

    public function getExpensesByStatus(string $status, int $workspaceId): Collection
    {
        return $this->expenseRepository->getExpensesByStatus($status, $workspaceId);
    }

    public function getExpensesByDateRange(string $startDate, string $endDate, int $workspaceId): Collection
    {
        return $this->expenseRepository->getExpensesByDateRange($startDate, $endDate, $workspaceId);
    }

    public function approveExpense(int $id, int $approverId): bool
    {
        return $this->updateExpense($id, [
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now()
        ]);
    }

    public function rejectExpense(int $id, int $approverId): bool
    {
        return $this->updateExpense($id, [
            'status' => 'rejected',
            'approved_by' => $approverId,
            'approved_at' => now()
        ]);
    }

    private function generateExpenseNumber(int $workspaceId): string
    {
        $year = date('Y');
        $month = date('m');
        
        $lastExpense = Expense::where('workspace_id', $workspaceId)
            ->where('expense_number', 'like', "EXP-{$year}{$month}%")
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastExpense) {
            $lastNumber = (int)substr($lastExpense->expense_number, -4);
            $nextNumber = $lastNumber + 1;
        }

        return "EXP-{$year}{$month}" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
