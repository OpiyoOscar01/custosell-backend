<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get active products
     */
    public function getActive(): Collection;

    /**
     * Get products by workspace
     */
    public function getByWorkspace(int $workspaceId): Collection;

    /**
     * Get products by branch
     */
    public function getByBranch(int $branchId): Collection;

    /**
     * Get products by category
     */
    public function getByCategory(int $categoryId): Collection;

    /**
     * Get products by brand
     */
    public function getByBrand(int $brandId): Collection;

    /**
     * Find product by SKU
     */
    public function findBySku(string $sku): ?Product;

    /**
     * Get featured products
     */
    public function getFeatured(): Collection;

    /**
     * Get low stock products
     */
    public function getLowStock(): Collection;

    /**
     * Search products by name or SKU
     */
    public function searchByNameOrSku(string $query): Collection;

    /**
     * Update stock quantity
     */
    public function updateStock(int $productId, int $quantity): bool;

    /**
     * Reduce stock quantity
     */
    public function reduceStock(int $productId, int $quantity): bool;

    /**
     * Increase stock quantity
     */
    public function increaseStock(int $productId, int $quantity): bool;
}
