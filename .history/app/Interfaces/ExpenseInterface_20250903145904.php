<?php

namespace App\Interfaces;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

interface ExpenseInterface
{
    public function getAllExpenses(int $workspaceId): Collection;
    public function getExpenseById(int $id): ?Expense;
    public function createExpense(array $data): Expense;
    public function updateExpense(int $id, array $data): bool;
    public function deleteExpense(int $id): bool;
    public function getExpensesByCategory(int $categoryId): Collection;
    public function getExpensesByProject(int $projectId): Collection;
    public function getExpensesByStatus(string $status, int $workspaceId): Collection;
    public function getExpensesByDateRange(string $startDate, string $endDate, int $workspaceId): Collection;
}
