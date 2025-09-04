<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends BaseApiController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;

        // Apply permission middleware
        $this->middleware('permission:view-products')->only(['index', 'show']);
        $this->middleware('permission:create-products')->only(['store']);
        $this->middleware('permission:update-products')->only(['update']);
        $this->middleware('permission:delete-products')->only(['destroy']);
    }

    /**
     * Display a listing of products.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', Product::class);

            $workspaceId = $request->get('workspace_id');
            $categoryId = $request->get('category_id');
            $brandId = $request->get('brand_id');
            $search = $request->get('search');
            $activeOnly = $request->get('active_only', false);

            if ($search && $workspaceId) {
                $products = $this->productService->searchProducts($search, $workspaceId);
            } elseif ($categoryId) {
                $products = $this->productService->getProductsByCategory($categoryId);
            } elseif ($brandId && $workspaceId) {
                $products = $this->productService->getProductsByBrand($brandId, $workspaceId);
            } elseif ($activeOnly && $workspaceId) {
                $products = $this->productService->getActiveProducts($workspaceId);
            } elseif ($workspaceId) {
                $products = $this->productService->getAllProducts($workspaceId);
            } else {
                return $this->errorResponse('Workspace ID is required', 400);
            }

            return $this->successResponse(
                ProductResource::collection($products),
                'Products retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', Product::class);

            $product = $this->productService->createProduct($request->validated());

            return $this->successResponse(
                new ProductResource($product),
                'Product created successfully',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            if (!$product) {
                return $this->errorResponse('Product not found', 404);
            }

            $this->authorize('view', $product);

            return $this->successResponse(
                new ProductResource($product),
                'Product retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update the specified product.
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            if (!$product) {
                return $this->errorResponse('Product not found', 404);
            }

            $this->authorize('update', $product);

            $updated = $this->productService->updateProduct($id, $request->validated());

            if (!$updated) {
                return $this->errorResponse('Failed to update product', 500);
            }

            $product = $this->productService->getProductById($id);

            return $this->successResponse(
                new ProductResource($product),
                'Product updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified product.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            if (!$product) {
                return $this->errorResponse('Product not found', 404);
            }

            $this->authorize('delete', $product);

            $deleted = $this->productService->deleteProduct($id);

            if (!$deleted) {
                return $this->errorResponse('Failed to delete product', 500);
            }

            return $this->successResponse(
                null,
                'Product deleted successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get low stock products.
     */
    public function lowStock(): JsonResponse
    {
        try {
            $this->authorize('viewAny', Product::class);

            $workspaceId = $request->user()->current_workspace_id;
            $products = $this->productService->getLowStockProducts($workspaceId);

            return $this->successResponse(
                ProductResource::collection($products),
                'Low stock products retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get products by category.
     */
    public function byCategory(int $categoryId): JsonResponse
    {
        try {
            $this->authorize('viewAny', Product::class);

            $products = $this->productService->getProductsByCategory($categoryId);

            return $this->successResponse(
                ProductResource::collection($products),
                'Products by category retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
