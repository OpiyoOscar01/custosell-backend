<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CompanyResource;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;

class CompanyController extends BaseApiController
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        parent::__construct();
        $this->companyService = $companyService;

        // Apply permission middleware
        $this->middleware('permission:view-companies')->only(['index', 'show']);
        $this->middleware('permission:create-companies')->only(['store']);
        $this->middleware('permission:update-companies')->only(['update']);
        $this->middleware('permission:delete-companies')->only(['destroy']);
    }

    /**
     * Display a listing of companies
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Company::class);

        try {
            $companies = $this->companyService->getAllCompanies();

            return $this->successResponse(
                $companies,
                'Companies retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve companies: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created company
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $this->authorize('create', Company::class);

        try {
            $company = $this->companyService->createCompany($request->validated());

            return $this->successResponse(
                new CompanyResource($company),
                'Company created successfully',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to create company: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Display the specified company
     */
    public function show(Company $company): JsonResponse
    {
        try {
            $this->authorize('view', $company);

            return $this->successResponse(
                new CompanyResource($company),
                'Company retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve company: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update the specified company
     */
    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        try {
            $this->authorize('update', $company);

            $updated = $this->companyService->updateCompany($company->id, $request->validated());

            if (!$updated) {
                return $this->errorResponse('Failed to update company', 500);
            }

            // Reload the company to get updated data
            $company->refresh();

            return $this->successResponse(
                new CompanyResource($company),
                'Company updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to update company: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Remove the specified company
     */
    public function destroy(Company $company): JsonResponse
    {
        try {
            $this->authorize('delete', $company);

            $deleted = $this->companyService->deleteCompany($company->id);

            if (!$deleted) {
                return $this->errorResponse('Failed to delete company', 500);
            }

            return $this->successResponse(
                null,
                'Company deleted successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to delete company: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get active companies
     */
    public function active(): JsonResponse
    {
        try {
            $companies = $this->companyService->getActiveCompanies();

            return response()->json([
                'success' => true,
                'data' => $companies,
                'message' => 'Active companies retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active companies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get companies with statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $companies = $this->companyService->getCompaniesWithStats();

            return response()->json([
                'success' => true,
                'data' => $companies,
                'message' => 'Company statistics retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve company statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search companies
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $companies = $this->companyService->searchCompanies($query);

            return response()->json([
                'success' => true,
                'data' => $companies,
                'message' => 'Company search completed successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search companies',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
