<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Services\BranchService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;

class BranchController extends BaseApiController
{
    protected $branchService;

    public function __construct(BranchService $branchService)
    {
        parent::__construct();
        $this->branchService = $branchService;

        // Apply permission middleware
        $this->middleware('permission:view-branches')->only(['index', 'show']);
        $this->middleware('permission:create-branches')->only(['store']);
        $this->middleware('permission:update-branches')->only(['update']);
        $this->middleware('permission:delete-branches')->only(['destroy']);
    }

    /**
     * Display a listing of branches
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Branch::class);
        
        try {
            $companyId = $request->get('company_id');
            
            if ($companyId) {
                $branches = $this->branchService->getBranchesByCompany($companyId);
            } else {
                $branches = $this->branchService->getAllBranches();
            }
            
            return $this->successResponse(
                $branches,
                'Branches retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve branches: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created branch
     */
    public function store(StoreBranchRequest $request): JsonResponse
    {
        try {
            $branch = $this->branchService->createBranch($request->validated());
            
            return response()->json([
                'success' => true,
                'data' => $branch,
                'message' => 'Branch created successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified branch
     */
    public function show(int $id): JsonResponse
    {
        try {
            $branch = $this->branchService->getBranchById($id);
            
            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $branch,
                'message' => 'Branch retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified branch
     */
    public function update(UpdateBranchRequest $request, int $id): JsonResponse
    {
        try {
            $updated = $this->branchService->updateBranch($id, $request->validated());
            
            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found'
                ], 404);
            }
            
            $branch = $this->branchService->getBranchById($id);
            
            return response()->json([
                'success' => true,
                'data' => $branch,
                'message' => 'Branch updated successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified branch
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->branchService->deleteBranch($id);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Branch not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Branch deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete branch',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active branches
     */
    public function active(): JsonResponse
    {
        try {
            $branches = $this->branchService->getActiveBranches();
            
            return response()->json([
                'success' => true,
                'data' => $branches,
                'message' => 'Active branches retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active branches',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get warehouse branches
     */
    public function warehouses(): JsonResponse
    {
        try {
            $branches = $this->branchService->getWarehouseBranches();
            
            return response()->json([
                'success' => true,
                'data' => $branches,
                'message' => 'Warehouse branches retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve warehouse branches',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get POS enabled branches
     */
    public function posEnabled(): JsonResponse
    {
        try {
            $branches = $this->branchService->getPosEnabledBranches();
            
            return response()->json([
                'success' => true,
                'data' => $branches,
                'message' => 'POS enabled branches retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve POS enabled branches',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get branches managed by user
     */
    public function managedBy(Request $request): JsonResponse
    {
        try {
            $userId = $request->get('user_id') ?? auth('sanctum')->id();
            $branches = $this->branchService->getBranchesManagedByUser($userId);
            
            return response()->json([
                'success' => true,
                'data' => $branches,
                'message' => 'Managed branches retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve managed branches',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
