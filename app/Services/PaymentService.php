<?php

namespace App\Services;

use App\Models\Payment;
use App\Interfaces\PaymentInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    protected $paymentRepository;

    public function __construct(PaymentInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getAllPayments(int $workspaceId): Collection
    {
        return $this->paymentRepository->getAllPayments($workspaceId);
    }

    public function getPaymentById(int $id): ?Payment
    {
        return $this->paymentRepository->getPaymentById($id);
    }

    public function createPayment(array $data): Payment
    {
        // Generate payment number if not provided
        if (!isset($data['payment_number'])) {
            $data['payment_number'] = $this->generatePaymentNumber($data['workspace_id']);
        }

        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        $payment = $this->paymentRepository->createPayment($data);

        // Update invoice paid amount if invoice_id is provided
        if (isset($data['invoice_id']) && $data['invoice_id']) {
            $this->updateInvoicePaidAmount($data['invoice_id']);
        }

        return $payment;
    }

    public function updatePayment(int $id, array $data): bool
    {
        $payment = $this->getPaymentById($id);
        if (!$payment) {
            return false;
        }

        $oldInvoiceId = $payment->invoice_id;
        $updated = $this->paymentRepository->updatePayment($id, $data);

        if ($updated) {
            // Update old invoice paid amount
            if ($oldInvoiceId) {
                $this->updateInvoicePaidAmount($oldInvoiceId);
            }

            // Update new invoice paid amount if changed
            if (isset($data['invoice_id']) && $data['invoice_id'] && $data['invoice_id'] != $oldInvoiceId) {
                $this->updateInvoicePaidAmount($data['invoice_id']);
            }
        }

        return $updated;
    }

    public function deletePayment(int $id): bool
    {
        $payment = $this->getPaymentById($id);
        if (!$payment) {
            return false;
        }

        $invoiceId = $payment->invoice_id;
        $deleted = $this->paymentRepository->deletePayment($id);

        // Update invoice paid amount
        if ($deleted && $invoiceId) {
            $this->updateInvoicePaidAmount($invoiceId);
        }

        return $deleted;
    }

    public function getPaymentsByStatus(string $status, int $workspaceId): Collection
    {
        return $this->paymentRepository->getPaymentsByStatus($status, $workspaceId);
    }

    public function getPaymentsByCustomer(int $customerId): Collection
    {
        return $this->paymentRepository->getPaymentsByCustomer($customerId);
    }

    public function getPaymentsByInvoice(int $invoiceId): Collection
    {
        return $this->paymentRepository->getPaymentsByInvoice($invoiceId);
    }

    public function getPaymentsByDateRange(string $startDate, string $endDate, int $workspaceId): Collection
    {
        return $this->paymentRepository->getPaymentsByDateRange($startDate, $endDate, $workspaceId);
    }

    private function generatePaymentNumber(int $workspaceId): string
    {
        $year = date('Y');

        $lastPayment = Payment::where('workspace_id', $workspaceId)
            ->where('payment_number', 'like', "PAY-{$year}%")
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastPayment) {
            $lastNumber = (int)substr($lastPayment->payment_number, -6);
            $nextNumber = $lastNumber + 1;
        }

        return "PAY-{$year}" . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    private function updateInvoicePaidAmount(int $invoiceId): void
    {
        $totalPaid = Payment::where('invoice_id', $invoiceId)
            ->where('status', 'completed')
            ->sum('amount');

        \App\Models\Invoice::where('id', $invoiceId)->update([
            'paid_amount' => $totalPaid,
            'balance_due' => DB::raw('total_amount - ' . $totalPaid)
        ]);
    }
}
