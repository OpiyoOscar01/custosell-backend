<?php

namespace App\Interfaces;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

interface CustomerInterface
{
    public function getAllCustomers(int $workspaceId): Collection;
    public function getCustomerById(int $id): ?Customer;
    public function createCustomer(array $data): Customer;
    public function updateCustomer(int $id, array $data): bool;
    public function deleteCustomer(int $id): bool;
    public function getActiveCustomers(int $workspaceId): Collection;
    public function searchCustomers(string $query, int $workspaceId): Collection;
    public function getCustomersByType(string $type, int $workspaceId): Collection;
}
