<?php

namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductInterface
{
    public function getAllProducts(int $workspaceId): Collection;
    public function getProductById(int $id): ?Product;
    public function createProduct(array $data): Product;
    public function updateProduct(int $id, array $data): bool;
    public function deleteProduct(int $id): bool;
    public function getActiveProducts(int $workspaceId): Collection;
    public function getProductsByCategory(int $categoryId): Collection;
    public function getProductsByBrand(int $brandId, int $workspaceId): Collection;
    public function getLowStockProducts(int $workspaceId): Collection;
    public function searchProducts(string $query, int $workspaceId): Collection;
    public function updateStock(int $id, int $quantity): bool;
}
