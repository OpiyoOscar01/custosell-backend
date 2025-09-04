<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Interfaces\CustomerInterface;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository implements CustomerInterface
{
    public function getAllCustomers(int $workspaceId): Collection
    {
        return Customer::where('workspace_id', $workspaceId)
            ->orderBy('name')
            ->get();
    }

    public function getCustomerById(int $id): ?Customer
    {
        return Customer::find($id);
    }

    public function createCustomer(array $data): Customer
    {
        return Customer::create($data);
    }

    public function updateCustomer(int $id, array $data): bool
    {
        return Customer::where('id', $id)->update($data);
    }

    public function deleteCustomer(int $id): bool
    {
        return Customer::destroy($id);
    }

    public function getActiveCustomers(int $workspaceId): Collection
    {
        return Customer::where('workspace_id', $workspaceId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function searchCustomers(string $query, int $workspaceId): Collection
    {
        return Customer::where('workspace_id', $workspaceId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('company', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->get();
    }

    public function getCustomersByType(string $type, int $workspaceId): Collection
    {
        return Customer::where('workspace_id', $workspaceId)
            ->where('type', $type)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
