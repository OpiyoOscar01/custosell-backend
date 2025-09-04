<?php

namespace App\Services;

use Exception;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Interfaces\CategoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(?int $workspaceId = null): Collection
    {
        if ($workspaceId) {
            return $this->categoryRepository->getAllCategories($workspaceId);
        }
        return $this->categoryRepository->all();
    }

    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->getCategoryById($id);
    }

    public function createCategory(array $data): Category
    {
        // Generate slug if not provided
        if (!isset($data['slug']) && isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Set sort order if not provided
        if (!isset($data['sort_order'])) {
            $lastCategory = Category::where('workspace_id', $data['workspace_id'])
                ->orderBy('sort_order', 'desc')
                ->first();
            $data['sort_order'] = $lastCategory ? $lastCategory->sort_order + 1 : 1;
        }

        return $this->categoryRepository->createCategory($data);
    }

    public function updateCategory(int $id, array $data): bool
    {
        // Update slug if name is changed
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->categoryRepository->updateCategory($id, $data);
    }

    public function deleteCategory(int $id): bool
    {
        $category = $this->getCategoryById($id);
        if (!$category) {
            return false;
        }

        // Check if category has children
        $children = $this->categoryRepository->getCategoryChildren($id);
        if ($children->count() > 0) {
            throw new Exception('Cannot delete category with subcategories');
        }

        return $this->categoryRepository->deleteCategory($id);
    }

    public function getCategoriesByType(string $type, int $workspaceId): Collection
    {
        return $this->categoryRepository->getCategoriesByType($type, $workspaceId);
    }

    public function getActiveCategories(int $workspaceId): Collection
    {
        return $this->categoryRepository->getActiveCategories($workspaceId);
    }

    public function getCategoryChildren(int $parentId): Collection
    {
        return $this->categoryRepository->getCategoryChildren($parentId);
    }
}