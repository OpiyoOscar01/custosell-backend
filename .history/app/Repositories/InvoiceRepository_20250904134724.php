<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Interfaces\InvoiceInterface;
use Illuminate\Database\Eloquent\Collection;

class InvoiceRepository implements InvoiceInterface
{
    public function getAllInvoices(int $workspaceId): Collection
    {
        return Invoice::where('workspace_id', $workspaceId)
            ->with(['customer', 'project', 'order', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getInvoiceById(int $id): ?Invoice
    {
        return Invoice::with(['customer', 'project', 'order', 'creator', 'payments'])
            ->find($id);
    }

    public function createInvoice(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function updateInvoice(int $id, array $data): bool
    {
        return Invoice::where('id', $id)->update($data);
    }

    public function deleteInvoice(int $id): bool
    {
        return Invoice::destroy($id);
    }

    public function getInvoicesByStatus(string $status, int $workspaceId): Collection
    {
        return Invoice::where('workspace_id', $workspaceId)
            ->where('status', $status)
            ->with(['customer', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getInvoicesByCustomer(int $customerId): Collection
    {
        return Invoice::where('customer_id', $customerId)
            ->with(['project', 'creator', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOverdueInvoices(int $workspaceId): Collection
    {
        return Invoice::where('workspace_id', $workspaceId)
            ->where('due_date', '<', now())
            ->where('status', '!=', 'paid')
            ->with(['customer', 'creator'])
            ->orderBy('due_date')
            ->get();
    }

    public function markAsSent(int $id): bool
    {
        return Invoice::where('id', $id)->update([
            'status' => 'sent',
            'sent_date' => now()
        ]);
    }

    public function markAsPaid(int $id): bool
    {
        return Invoice::where('id', $id)->update([
            'status' => 'paid',
            'paid_date' => now()
        ]);
    }

    public function searchInvoices(string $query, int $workspaceId): Collection
    {
        return Invoice::where('workspace_id', $workspaceId)
            ->where(function ($q) use ($query) {
                $q->where('invoice_number', 'like', "%{$query}%")
                    ->orWhere('notes', 'like', "%{$query}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($query) {
                        $customerQuery->where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
                    });
            })
            ->with(['customer', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function sendInvoiceEmail(int $id): bool
    {
        // This would typically integrate with an email service
        // For now, we'll just mark it as sent
        return Invoice::where('id', $id)->update([
            'status' => 'sent',
            'sent_date' => now()
        ]);
    }
}
