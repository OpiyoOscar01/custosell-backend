<?php

namespace App\Services;

use App\Models\Product;
use App\Interfaces\ProductInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(int $workspaceId): Collection
    {
        return $this->productRepository->getAllProducts($workspaceId);
    }

    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->getProductById($id);
    }

    public function createProduct(array $data): Product
    {
        // Generate SKU if not provided
        if (!isset($data['sku'])) {
            $data['sku'] = $this->generateSKU($data['workspace_id']);
        }

        return $this->productRepository->createProduct($data);
    }

    public function updateProduct(int $id, array $data): bool
    {
        return $this->productRepository->updateProduct($id, $data);
    }

    public function deleteProduct(int $id): bool
    {
        return $this->productRepository->deleteProduct($id);
    }

    public function getActiveProducts(int $workspaceId): Collection
    {
        return $this->productRepository->getActiveProducts($workspaceId);
    }

    public function getProductsByCategory(int $categoryId): Collection
    {
        return $this->productRepository->getProductsByCategory($categoryId);
    }

    public function getProductsByBrand(int $brandId, int $workspaceId): Collection
    {
        return $this->productRepository->getProductsByBrand($brandId, $workspaceId);
    }

    public function getLowStockProducts(int $workspaceId): Collection
    {
        return $this->productRepository->getLowStockProducts($workspaceId);
    }

    public function searchProducts(string $query, int $workspaceId): Collection
    {
        return $this->productRepository->searchProducts($query, $workspaceId);
    }

    public function updateStock(int $id, int $quantity): bool
    {
        return $this->productRepository->updateStock($id, $quantity);
    }

    private function generateSKU(int $workspaceId): string
    {
        $lastProduct = Product::where('workspace_id', $workspaceId)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastProduct ? (int)substr($lastProduct->sku, -4) + 1 : 1;
        return 'SKU' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
