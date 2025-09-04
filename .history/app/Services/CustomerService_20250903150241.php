<?php

namespace App\Services;

use App\Models\Customer;
use App\Interfaces\CustomerInterface;
use Illuminate\Database\Eloquent\Collection;

class CustomerService
{
    protected $customerRepository;

    public function __construct(CustomerInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getAllCustomers(int $workspaceId): Collection
    {
        return $this->customerRepository->getAllCustomers($workspaceId);
    }

    public function getCustomerById(int $id): ?Customer
    {
        return $this->customerRepository->getCustomerById($id);
    }

    public function createCustomer(array $data): Customer
    {
        // Generate customer code if not provided
        if (!isset($data['customer_code'])) {
            $data['customer_code'] = $this->generateCustomerCode($data['workspace_id']);
        }

        return $this->customerRepository->createCustomer($data);
    }

    public function updateCustomer(int $id, array $data): bool
    {
        return $this->customerRepository->updateCustomer($id, $data);
    }

    public function deleteCustomer(int $id): bool
    {
        $customer = $this->getCustomerById($id);
        if (!$customer) {
            return false;
        }

        // Check if customer has orders or invoices
        if ($customer->orders()->count() > 0 || $customer->invoices()->count() > 0) {
            throw new \Exception('Cannot delete customer with existing orders or invoices');
        }

        return $this->customerRepository->deleteCustomer($id);
    }

    public function getActiveCustomers(int $workspaceId): Collection
    {
        return $this->customerRepository->getActiveCustomers($workspaceId);
    }

    public function searchCustomers(string $query, int $workspaceId): Collection
    {
        return $this->customerRepository->searchCustomers($query, $workspaceId);
    }

    public function getCustomersByType(string $type, int $workspaceId): Collection
    {
        return $this->customerRepository->getCustomersByType($type, $workspaceId);
    }

    private function generateCustomerCode(int $workspaceId): string
    {
        $lastCustomer = Customer::where('workspace_id', $workspaceId)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastCustomer ? (int)substr($lastCustomer->customer_code, -4) + 1 : 1;
        return 'CUST' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
