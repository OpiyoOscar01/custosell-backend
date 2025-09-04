<?php

namespace App\Services;

use App\Models\Invoice;
use App\Interfaces\InvoiceInterface;
use Illuminate\Database\Eloquent\Collection;

class InvoiceService
{
    protected $invoiceRepository;

    public function __construct(InvoiceInterface $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function getAllInvoices(int $workspaceId): Collection
    {
        return $this->invoiceRepository->getAllInvoices($workspaceId);
    }

    public function getInvoiceById(int $id): ?Invoice
    {
        return $this->invoiceRepository->getInvoiceById($id);
    }

    public function createInvoice(array $data): Invoice
    {
        // Generate invoice number if not provided
        if (!isset($data['invoice_number'])) {
            $data['invoice_number'] = $this->generateInvoiceNumber($data['workspace_id']);
        }

        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        // Calculate balance due
        $data['balance_due'] = $data['total_amount'] - ($data['paid_amount'] ?? 0);

        return $this->invoiceRepository->createInvoice($data);
    }

    public function updateInvoice(int $id, array $data): bool
    {
        // Recalculate balance due if amounts change
        if (isset($data['total_amount']) || isset($data['paid_amount'])) {
            $invoice = $this->getInvoiceById($id);
            if ($invoice) {
                $totalAmount = $data['total_amount'] ?? $invoice->total_amount;
                $paidAmount = $data['paid_amount'] ?? $invoice->paid_amount;
                $data['balance_due'] = $totalAmount - $paidAmount;
            }
        }

        return $this->invoiceRepository->updateInvoice($id, $data);
    }

    public function deleteInvoice(int $id): bool
    {
        $invoice = $this->getInvoiceById($id);
        if (!$invoice) {
            return false;
        }

        // Check if invoice has payments
        if ($invoice->payments()->count() > 0) {
            throw new \Exception('Cannot delete invoice that has received payments');
        }

        return $this->invoiceRepository->deleteInvoice($id);
    }

    public function getInvoicesByStatus(string $status, int $workspaceId): Collection
    {
        return $this->invoiceRepository->getInvoicesByStatus($status, $workspaceId);
    }

    public function getInvoicesByCustomer(int $customerId): Collection
    {
        return $this->invoiceRepository->getInvoicesByCustomer($customerId);
    }

    public function getOverdueInvoices(int $workspaceId): Collection
    {
        return $this->invoiceRepository->getOverdueInvoices($workspaceId);
    }

    public function markAsSent(int $id): bool
    {
        return $this->invoiceRepository->markAsSent($id);
    }

    public function markAsPaid(int $id): bool
    {
        $updated = $this->invoiceRepository->markAsPaid($id);
        
        if ($updated) {
            // Update balance due to 0
            $this->updateInvoice($id, ['balance_due' => 0]);
        }

        return $updated;
    }

    private function generateInvoiceNumber(int $workspaceId): string
    {
        $year = date('Y');
        $month = date('m');
        
        $lastInvoice = Invoice::where('workspace_id', $workspaceId)
            ->where('invoice_number', 'like', "INV-{$year}{$month}%")
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastInvoice) {
            $lastNumber = (int)substr($lastInvoice->invoice_number, -4);
            $nextNumber = $lastNumber + 1;
        }

        return "INV-{$year}{$month}" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
