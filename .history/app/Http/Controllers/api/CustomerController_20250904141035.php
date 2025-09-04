<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerController extends BaseApiController
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        parent::__construct();
        $this->customerService = $customerService;

        // Apply permission middleware
        $this->middleware('permission:view-customers')->only(['index', 'show']);
        $this->middleware('permission:create-customers')->only(['store']);
        $this->middleware('permission:update-customers')->only(['update']);
        $this->middleware('permission:delete-customers')->only(['destroy']);
    }

    /**
     * Display a listing of customers.
     */
    public function index(Request $request): JsonResponse
    {
        // Check policy before proceeding
        $this->authorize('viewAny', Customer::class);

        $workspaceId = $request->get('workspace_id');
        $type = $request->get('type');
        $search = $request->get('search');
        $activeOnly = $request->get('active_only', false);

        if (!$workspaceId) {
            return response()->json([
                'success' => false,
                'message' => 'Workspace ID is required'
            ], 400);
        }

        if ($search) {
            $customers = $this->customerService->searchCustomers($search, $workspaceId);
        } elseif ($type) {
            $customers = $this->customerService->getCustomersByType($type, $workspaceId);
        } elseif ($activeOnly) {
            $customers = $this->customerService->getActiveCustomers($workspaceId);
        } else {
            $customers = $this->customerService->getAllCustomers($workspaceId);
        }

        return response()->json([
            'success' => true,
            'data' => CustomerResource::collection($customers),
            'message' => 'Customers retrieved successfully'
        ]);
    }

    /**
     * Store a newly created customer.
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', Customer::class);
            
            $data = $request->validated();
            $data['created_by'] = $request->user()->id;
            
            $customer = $this->customerService->createCustomer($data);

            return response()->json([
                'success' => true,
                'data' => new CustomerResource($customer),
                'message' => 'Customer created successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified customer.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $customer = $this->customerService->getCustomerById($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            // Load relationships for detailed view
            $customer->load(['projects', 'orders', 'invoices', 'payments']);

            return response()->json([
                'success' => true,
                'data' => new CustomerResource($customer),
                'message' => 'Customer retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified customer.
     */
    public function update(UpdateCustomerRequest $request, int $id): JsonResponse
    {
        try {
            $updated = $this->customerService->updateCustomer($id, $request->validated());

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $customer = $this->customerService->getCustomerById($id);

            return response()->json([
                'success' => true,
                'data' => new CustomerResource($customer),
                'message' => 'Customer updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->customerService->deleteCustomer($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer: ' . $e->getMessage()
            ], 400);
        }
    }
}
