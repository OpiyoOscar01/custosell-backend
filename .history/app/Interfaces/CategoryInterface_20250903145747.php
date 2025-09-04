<?php

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryInterface
{
    public function getAllCategories(int $workspaceId): Collection;
    public function getCategoryById(int $id): ?Category;
    public function createCategory(array $data): Category;
    public function updateCategory(int $id, array $data): bool;
    public function deleteCategory(int $id): bool;
    public function getCategoriesByType(string $type, int $workspaceId): Collection;
    public function getActiveCategories(int $workspaceId): Collection;
    public function getCategoryChildren(int $parentId): Collection;
}
