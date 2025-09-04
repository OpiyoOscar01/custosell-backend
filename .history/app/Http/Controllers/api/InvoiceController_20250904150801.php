<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\InvoiceResource;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;

class InvoiceController extends BaseApiController
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        parent::__construct();
        $this->invoiceService = $invoiceService;

        // Apply permission middleware
        $this->middleware('permission:view-invoices')->only(['index', 'show']);
        $this->middleware('permission:create-invoices')->only(['store']);
        $this->middleware('permission:update-invoices')->only(['update']);
        $this->middleware('permission:delete-invoices')->only(['destroy']);
    }

    /**
     * Display a listing of invoices.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', Invoice::class);

            $user = $request->user();
            $workspaceId = $user->workspaces()->first()?->id ?? 1;
            $status = $request->get('status');
            $customerId = $request->get('customer_id');
            $search = $request->get('search');

            if ($search) {
                $invoices = $this->invoiceService->searchInvoices($search, $workspaceId);
            } elseif ($status) {
                $invoices = $this->invoiceService->getInvoicesByStatus($status, $workspaceId);
            } elseif ($customerId) {
                $invoices = $this->invoiceService->getInvoicesByCustomer($customerId);
            } else {
                $invoices = $this->invoiceService->getAllInvoices($workspaceId);
            }

            return $this->successResponse(
                InvoiceResource::collection($invoices),
                'Invoices retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created invoice.
     */
    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', Invoice::class);

            $invoice = $this->invoiceService->createInvoice($request->validated());

            return $this->successResponse(
                new InvoiceResource($invoice),
                'Invoice created successfully',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $invoice = $this->invoiceService->getInvoiceById($id);

            if (!$invoice) {
                return $this->errorResponse('Invoice not found', 404);
            }

            $this->authorize('view', $invoice);

            return $this->successResponse(
                new InvoiceResource($invoice),
                'Invoice retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update the specified invoice.
     */
    public function update(UpdateInvoiceRequest $request, int $id): JsonResponse
    {
        try {
            $invoice = $this->invoiceService->getInvoiceById($id);

            if (!$invoice) {
                return $this->errorResponse('Invoice not found', 404);
            }

            $this->authorize('update', $invoice);

            $updated = $this->invoiceService->updateInvoice($id, $request->validated());

            if (!$updated) {
                return $this->errorResponse('Failed to update invoice', 500);
            }

            $invoice = $this->invoiceService->getInvoiceById($id);

            return $this->successResponse(
                new InvoiceResource($invoice),
                'Invoice updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified invoice.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $invoice = $this->invoiceService->getInvoiceById($id);

            if (!$invoice) {
                return $this->errorResponse('Invoice not found', 404);
            }

            $this->authorize('delete', $invoice);

            $deleted = $this->invoiceService->deleteInvoice($id);

            if (!$deleted) {
                return $this->errorResponse('Failed to delete invoice', 500);
            }

            return $this->successResponse(
                null,
                'Invoice deleted successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get overdue invoices.
     */
    public function overdue(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', Invoice::class);

            $workspaceId = $request->user()->current_workspace_id;
            $invoices = $this->invoiceService->getOverdueInvoices($workspaceId);

            return $this->successResponse(
                InvoiceResource::collection($invoices),
                'Overdue invoices retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Send invoice via email.
     */
    public function sendEmail(int $id): JsonResponse
    {
        try {
            $invoice = $this->invoiceService->getInvoiceById($id);

            if (!$invoice) {
                return $this->errorResponse('Invoice not found', 404);
            }

            $this->authorize('update', $invoice);

            $sent = $this->invoiceService->sendInvoiceEmail($id);

            if (!$sent) {
                return $this->errorResponse('Failed to send invoice email', 500);
            }

            return $this->successResponse(
                null,
                'Invoice email sent successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get active invoices.
     */
    public function active(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', Invoice::class);

            $user = $request->user();
            $workspaceId = $user->workspaces()->first()?->id ?? 1;
            $invoices = $this->invoiceService->getInvoicesByStatus('active', $workspaceId);

            return $this->successResponse(
                InvoiceResource::collection($invoices),
                'Active invoices retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get invoices by status.
     */
    public function byStatus(Request $request, string $status): JsonResponse
    {
        try {
            $this->authorize('viewAny', Invoice::class);

            $user = $request->user();
            $workspaceId = $user->workspaces()->first()?->id ?? 1;
            $invoices = $this->invoiceService->getInvoicesByStatus($status, $workspaceId);

            return $this->successResponse(
                InvoiceResource::collection($invoices),
                'Invoices by status retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get invoice statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', Invoice::class);

            $user = $request->user();
            $workspaceId = $user->workspaces()->first()?->id ?? 1;
            $allInvoices = $this->invoiceService->getAllInvoices($workspaceId);
            $overdueInvoices = $this->invoiceService->getOverdueInvoices($workspaceId);

            $stats = [
                'total_invoices' => $allInvoices->count(),
                'overdue_invoices' => $overdueInvoices->count(),
                'total_amount' => $allInvoices->sum('total_amount'),
                'overdue_amount' => $overdueInvoices->sum('total_amount')
            ];

            return $this->successResponse(
                $stats,
                'Invoice statistics retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
