<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Services\UnitService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UnitResource;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;

class UnitController extends BaseApiController
{
    protected $unitService;

    public function __construct(UnitService $unitService)
    {
        parent::__construct();
        $this->unitService = $unitService;

        // Apply permission middleware
        $this->middleware('permission:view-units')->only(['index', 'show']);
        $this->middleware('permission:create-units')->only(['store']);
        $this->middleware('permission:update-units')->only(['update']);
        $this->middleware('permission:delete-units')->only(['destroy']);
    }

    /**
     * Display a listing of units
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $companyId = $request->get('company_id');
            $type = $request->get('type');

            if ($companyId) {
                $units = $this->unitService->getUnitsByCompany($companyId);
            } elseif ($type) {
                $units = $this->unitService->getUnitsByType($type);
            } else {
                $units = $this->unitService->getAllUnits();
            }

            return response()->json([
                'success' => true,
                'data' => $units,
                'message' => 'Units retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve units',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created unit
     */
    public function store(StoreUnitRequest $request): JsonResponse
    {
        try {
            $unit = $this->unitService->createUnit($request->validated());

            return response()->json([
                'success' => true,
                'data' => $unit,
                'message' => 'Unit created successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create unit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified unit
     */
    public function show(int $id): JsonResponse
    {
        try {
            $unit = $this->unitService->getUnitById($id);

            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $unit,
                'message' => 'Unit retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve unit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified unit
     */
    public function update(UpdateUnitRequest $request, Unit $unit): JsonResponse
    {
        try {
            $this->authorize('update', $unit);
            
            $updated = $this->unitService->updateUnit($unit->id, $request->validated());

            if (!$updated) {
                return $this->errorResponse('Unit not found', 404);
            }

            $unit->refresh();

            return $this->successResponse(
                new UnitResource($unit), 
                'Unit updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified unit
     */
    public function destroy(Unit $unit): JsonResponse
    {
        try {
            $this->authorize('delete', $unit);
            
            $deleted = $this->unitService->deleteUnit($unit->id);

            if (!$deleted) {
                return $this->errorResponse('Unit not found', 404);
            }

            return $this->successResponse(null, 'Unit deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get active units
     */
    public function active(): JsonResponse
    {
        try {
            $units = $this->unitService->getActiveUnits();

            return response()->json([
                'success' => true,
                'data' => $units,
                'message' => 'Active units retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active units',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
