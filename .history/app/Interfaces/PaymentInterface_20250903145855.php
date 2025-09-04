<?php

namespace App\Interfaces;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

interface PaymentInterface
{
    public function getAllPayments(int $workspaceId): Collection;
    public function getPaymentById(int $id): ?Payment;
    public function createPayment(array $data): Payment;
    public function updatePayment(int $id, array $data): bool;
    public function deletePayment(int $id): bool;
    public function getPaymentsByStatus(string $status, int $workspaceId): Collection;
    public function getPaymentsByCustomer(int $customerId): Collection;
    public function getPaymentsByInvoice(int $invoiceId): Collection;
    public function getPaymentsByDateRange(string $startDate, string $endDate, int $workspaceId): Collection;
}
