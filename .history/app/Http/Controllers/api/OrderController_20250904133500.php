<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends BaseApiController
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;

        // Apply permission middleware
        $this->middleware('permission:view-orders')->only(['index', 'show']);
        $this->middleware('permission:create-orders')->only(['store']);
        $this->middleware('permission:update-orders')->only(['update']);
        $this->middleware('permission:delete-orders')->only(['destroy']);
    }

    /**
     * Display a listing of orders.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', Order::class);

            $workspaceId = Auth::user()->current_workspace_id;
            $status = $request->get('status');
            $customerId = $request->get('customer_id');
            $search = $request->get('search');

            if ($search) {
                $orders = $this->orderService->searchOrders($search, $workspaceId);
            } elseif ($status) {
                $orders = $this->orderService->getOrdersByStatus($status, $workspaceId);
            } elseif ($customerId) {
                $orders = $this->orderService->getOrdersByCustomer($customerId);
            } else {
                $orders = $this->orderService->getAllOrders($workspaceId);
            }

            return $this->successResponse(
                OrderResource::collection($orders),
                'Orders retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created order.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', Order::class);

            $order = $this->orderService->createOrder($request->validated());

            return $this->successResponse(
                new OrderResource($order),
                'Order created successfully',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified order.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderById($id);

            if (!$order) {
                return $this->errorResponse('Order not found', 404);
            }

            $this->authorize('view', $order);

            return $this->successResponse(
                new OrderResource($order),
                'Order retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update the specified order.
     */
    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderById($id);

            if (!$order) {
                return $this->errorResponse('Order not found', 404);
            }

            $this->authorize('update', $order);

            $updated = $this->orderService->updateOrder($id, $request->validated());

            if (!$updated) {
                return $this->errorResponse('Failed to update order', 500);
            }

            $order = $this->orderService->getOrderById($id);

            return $this->successResponse(
                new OrderResource($order),
                'Order updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified order.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderById($id);

            if (!$order) {
                return $this->errorResponse('Order not found', 404);
            }

            $this->authorize('delete', $order);

            $deleted = $this->orderService->deleteOrder($id);

            if (!$deleted) {
                return $this->errorResponse('Failed to delete order', 500);
            }

            return $this->successResponse(
                null,
                'Order deleted successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get pending orders.
     */
    public function pending(): JsonResponse
    {
        try {
            $this->authorize('viewAny', Order::class);

            $workspaceId = Auth::user()->current_workspace_id;
            $orders = $this->orderService->getPendingOrders($workspaceId);

            return $this->successResponse(
                OrderResource::collection($orders),
                'Pending orders retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderById($id);

            if (!$order) {
                return $this->errorResponse('Order not found', 404);
            }

            $this->authorize('update', $order);

            $updated = $this->orderService->updateOrderStatus($id, $request->get('status'));

            if (!$updated) {
                return $this->errorResponse('Failed to update order status', 500);
            }

            $order = $this->orderService->getOrderById($id);

            return $this->successResponse(
                new OrderResource($order),
                'Order status updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
