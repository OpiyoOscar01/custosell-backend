<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Interfaces\PaymentInterface;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository implements PaymentInterface
{
    public function getAllPayments(int $workspaceId): Collection
    {
        return Payment::where('workspace_id', $workspaceId)
            ->with(['customer', 'invoice', 'order', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPaymentById(int $id): ?Payment
    {
        return Payment::with(['customer', 'invoice', 'order', 'creator'])
            ->find($id);
    }

    public function createPayment(array $data): Payment
    {
        return Payment::create($data);
    }

    public function updatePayment(int $id, array $data): bool
    {
        return Payment::where('id', $id)->update($data);
    }

    public function deletePayment(int $id): bool
    {
        return Payment::destroy($id);
    }

    public function getPaymentsByStatus(string $status, int $workspaceId): Collection
    {
        return Payment::where('workspace_id', $workspaceId)
            ->where('status', $status)
            ->with(['customer', 'invoice', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPaymentsByCustomer(int $customerId): Collection
    {
        return Payment::where('customer_id', $customerId)
            ->with(['invoice', 'order', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPaymentsByInvoice(int $invoiceId): Collection
    {
        return Payment::where('invoice_id', $invoiceId)
            ->with(['customer', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPaymentsByDateRange(string $startDate, string $endDate, int $workspaceId): Collection
    {
        return Payment::where('workspace_id', $workspaceId)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->with(['customer', 'invoice', 'creator'])
            ->orderBy('payment_date', 'desc')
            ->get();
    }
}
