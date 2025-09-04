<?php

namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\CategoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryInterface
{
    public function all(): Collection
    {
        return Category::all();
    }

    public function getAllCategories(int $workspaceId): Collection
    {
        return Category::where('workspace_id', $workspaceId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function getCategoryById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function updateCategory(int $id, array $data): bool
    {
        return Category::where('id', $id)->update($data);
    }

    public function deleteCategory(int $id): bool
    {
        return Category::destroy($id);
    }

    public function getCategoriesByType(string $type, int $workspaceId): Collection
    {
        return Category::where('workspace_id', $workspaceId)
            ->where('type', $type)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getActiveCategories(int $workspaceId): Collection
    {
        return Category::where('workspace_id', $workspaceId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getCategoryChildren(int $parentId): Collection
    {
        return Category::where('parent_id', $parentId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
