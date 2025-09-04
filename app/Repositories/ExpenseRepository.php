<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Interfaces\ExpenseInterface;
use Illuminate\Database\Eloquent\Collection;

class ExpenseRepository implements ExpenseInterface
{
    public function getAllExpenses(int $workspaceId): Collection
    {
        return Expense::where('workspace_id', $workspaceId)
            ->with(['category', 'project', 'customer', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getExpenseById(int $id): ?Expense
    {
        return Expense::with(['category', 'project', 'customer', 'creator', 'approver'])
            ->find($id);
    }

    public function createExpense(array $data): Expense
    {
        return Expense::create($data);
    }

    public function updateExpense(int $id, array $data): bool
    {
        return Expense::where('id', $id)->update($data);
    }

    public function deleteExpense(int $id): bool
    {
        return Expense::destroy($id);
    }

    public function getExpensesByCategory(int $categoryId): Collection
    {
        return Expense::where('category_id', $categoryId)
            ->with(['project', 'customer', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getExpensesByProject(int $projectId): Collection
    {
        return Expense::where('project_id', $projectId)
            ->with(['category', 'customer', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getExpensesByStatus(string $status, int $workspaceId): Collection
    {
        return Expense::where('workspace_id', $workspaceId)
            ->where('status', $status)
            ->with(['category', 'project', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getExpensesByDateRange(string $startDate, string $endDate, int $workspaceId): Collection
    {
        return Expense::where('workspace_id', $workspaceId)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->with(['category', 'project', 'creator'])
            ->orderBy('expense_date', 'desc')
            ->get();
    }
}
