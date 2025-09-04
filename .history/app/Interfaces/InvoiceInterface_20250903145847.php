<?php

namespace App\Interfaces;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;

interface InvoiceInterface
{
    public function getAllInvoices(int $workspaceId): Collection;
    public function getInvoiceById(int $id): ?Invoice;
    public function createInvoice(array $data): Invoice;
    public function updateInvoice(int $id, array $data): bool;
    public function deleteInvoice(int $id): bool;
    public function getInvoicesByStatus(string $status, int $workspaceId): Collection;
    public function getInvoicesByCustomer(int $customerId): Collection;
    public function getOverdueInvoices(int $workspaceId): Collection;
    public function markAsSent(int $id): bool;
    public function markAsPaid(int $id): bool;
}
