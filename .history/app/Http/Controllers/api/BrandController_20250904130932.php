<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Services\BrandService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BrandResource;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;

class BrandController extends BaseApiController
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        parent::__construct();
        $this->brandService = $brandService;

        // Apply permission middleware
        $this->middleware('permission:view-brands')->only(['index', 'show']);
        $this->middleware('permission:create-brands')->only(['store']);
        $this->middleware('permission:update-brands')->only(['update']);
        $this->middleware('permission:delete-brands')->only(['destroy']);
    }

    /**
     * Display a listing of brands
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $companyId = $request->get('company_id');

            if ($companyId) {
                $brands = $this->brandService->getBrandsByCompany($companyId);
            } else {
                $brands = $this->brandService->getAllBrands();
            }

            return response()->json([
                'success' => true,
                'data' => $brands,
                'message' => 'Brands retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve brands',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created brand
     */
    public function store(StoreBrandRequest $request): JsonResponse
    {
        try {
            $brand = $this->brandService->createBrand($request->validated());

            return response()->json([
                'success' => true,
                'data' => $brand,
                'message' => 'Brand created successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create brand',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified brand
     */
    public function show(int $id): JsonResponse
    {
        try {
            $brand = $this->brandService->getBrandById($id);

            if (!$brand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Brand not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $brand,
                'message' => 'Brand retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve brand',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified brand
     */
    public function update(UpdateBrandRequest $request, Brand $brand): JsonResponse
    {
        try {
            $this->authorize('update', $brand);
            
            $updated = $this->brandService->updateBrand($brand->id, $request->validated());

            if (!$updated) {
                return $this->errorResponse('Brand not found', 404);
            }

            $brand->refresh();

            return $this->successResponse(
                new BrandResource($brand), 
                'Brand updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified brand
     */
    public function destroy(Brand $brand): JsonResponse
    {
        try {
            $this->authorize('delete', $brand);
            
            $deleted = $this->brandService->deleteBrand($brand->id);

            if (!$deleted) {
                return $this->errorResponse('Brand not found', 404);
            }

            return $this->successResponse(null, 'Brand deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get active brands
     */
    public function active(): JsonResponse
    {
        try {
            $brands = $this->brandService->getActiveBrands();

            return response()->json([
                'success' => true,
                'data' => $brands,
                'message' => 'Active brands retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active brands',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
