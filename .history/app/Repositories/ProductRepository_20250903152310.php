<?php

namespace App\Repositories;

use App\Models\Product;
use App\Interfaces\ProductInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductInterface
{
    public function getAllProducts(int $workspaceId): Collection
    {
        return Product::where('workspace_id', $workspaceId)
            ->with('category')
            ->orderBy('name')
            ->get();
    }

    public function getProductById(int $id): ?Product
    {
        return Product::with('category')->find($id);
    }

    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct(int $id, array $data): bool
    {
        return Product::where('id', $id)->update($data);
    }

    public function deleteProduct(int $id): bool
    {
        return Product::destroy($id);
    }

    public function getActiveProducts(int $workspaceId): Collection
    {
        return Product::where('workspace_id', $workspaceId)
            ->where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();
    }

    public function getProductsByCategory(int $categoryId): Collection
    {
        return Product::where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function searchProducts(string $query, int $workspaceId): Collection
    {
        return Product::where('workspace_id', $workspaceId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('category')
            ->orderBy('name')
            ->get();
    }

    public function updateStock(int $id, int $quantity): bool
    {
        return Product::where('id', $id)->update(['stock_quantity' => $quantity]);
    }
}
