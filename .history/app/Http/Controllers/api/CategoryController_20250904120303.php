<?php

namespace App\Http\Controllers\Api;
use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends BaseApiController
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;

        // Apply permission middleware
        $this->middleware('permission:view-categories')->only(['index', 'show']);
        $this->middleware('permission:create-categories')->only(['store']);
        $this->middleware('permission:update-categories')->only(['update']);
        $this->middleware('permission:delete-categories')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Check policy before proceeding
            $this->authorize('viewAny', Category::class);

            $workspaceId = $request->get('workspace_id');
            $categories = $this->categoryService->getAllCategories($workspaceId);
            
            return $this->successResponse(
                CategoryResource::collection($categories),
                'Categories retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve categories: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            // Check policy before proceeding
            $this->authorize('create', Category::class);

            $category = $this->categoryService->createCategory($request->validated());
            
            return $this->successResponse(
                new CategoryResource($category),
                'Category created successfully',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to create category: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $category = $this->categoryService->getCategoryById($id);

            // Check policy with the specific category
            $this->authorize('view', $category);

            return $this->successResponse(
                new CategoryResource($category),
                'Category retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Category not found: ' . $e->getMessage(),
                404
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id): JsonResponse
    {
        try {
            $category = $this->categoryService->getCategoryById($id);

            // Check policy with the specific category
            $this->authorize('update', $category);

            $category = $this->categoryService->updateCategory($id, $request->validated());
            
            return $this->successResponse(
                new CategoryResource($category),
                'Category updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to update category: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $category = $this->categoryService->getCategoryById($id);

            // Check policy with the specific category
            $this->authorize('delete', $category);

            $this->categoryService->deleteCategory($id);
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}