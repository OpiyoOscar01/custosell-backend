<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * Get active products
     */
    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    /**
     * Get products by workspace
     */
    public function getByWorkspace(int $workspaceId): Collection
    {
        return $this->model->where('workspace_id', $workspaceId)->get();
    }

    /**
     * Get products by branch
     */
    public function getByBranch(int $branchId): Collection
    {
        return $this->model->where('branch_id', $branchId)->get();
    }

    /**
     * Get products by category
     */
    public function getByCategory(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)->get();
    }

    /**
     * Get products by brand
     */
    public function getByBrand(int $brandId): Collection
    {
        return $this->model->where('brand_id', $brandId)->get();
    }

    /**
     * Find product by SKU
     */
    public function findBySku(string $sku): ?Product
    {
        return $this->model->where('sku', $sku)->first();
    }

    /**
     * Get featured products
     */
    public function getFeatured(): Collection
    {
        return $this->model->featured()->get();
    }

    /**
     * Get low stock products
     */
    public function getLowStock(): Collection
    {
        return $this->model->lowStock()->get();
    }

    /**
     * Search products by name or SKU
     */
    public function searchByNameOrSku(string $query): Collection
    {
        return $this->model->where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->get();
    }

    /**
     * Update stock quantity
     */
    public function updateStock(int $productId, int $quantity): bool
    {
        return $this->model->where('id', $productId)->update(['stock_quantity' => $quantity]);
    }

    /**
     * Reduce stock quantity
     */
    public function reduceStock(int $productId, int $quantity): bool
    {
        $product = $this->findOrFail($productId);
        return $product->reduceStock($quantity);
    }

    /**
     * Increase stock quantity
     */
    public function increaseStock(int $productId, int $quantity): bool
    {
        $product = $this->findOrFail($productId);
        $product->increaseStock($quantity);
        return true;
    }
}
