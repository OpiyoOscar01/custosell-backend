<?php

namespace App\Services;

use App\Models\Order;
use App\Interfaces\OrderInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAllOrders(int $workspaceId): Collection
    {
        return $this->orderRepository->getAllOrders($workspaceId);
    }

    public function getOrderById(int $id): ?Order
    {
        return $this->orderRepository->getOrderById($id);
    }

    public function createOrder(array $data): Order
    {
        // Generate order number if not provided
        if (!isset($data['order_number'])) {
            $data['order_number'] = $this->generateOrderNumber($data['workspace_id']);
        }

        return $this->orderRepository->createOrder($data);
    }

    public function updateOrder(int $id, array $data): bool
    {
        return $this->orderRepository->updateOrder($id, $data);
    }

    public function deleteOrder(int $id): bool
    {
        return $this->orderRepository->deleteOrder($id);
    }

    public function getOrdersByStatus(string $status, int $workspaceId): Collection
    {
        return $this->orderRepository->getOrdersByStatus($status, $workspaceId);
    }

    public function getOrdersByCustomer(int $customerId): Collection
    {
        return $this->orderRepository->getOrdersByCustomer($customerId);
    }

    public function getOrdersByDate(string $startDate, string $endDate, int $workspaceId): Collection
    {
        return $this->orderRepository->getOrdersByDate($startDate, $endDate, $workspaceId);
    }

    public function updateOrderStatus(int $id, string $status): bool
    {
        return $this->orderRepository->updateOrderStatus($id, $status);
    }

    private function generateOrderNumber(int $workspaceId): string
    {
        $lastOrder = Order::where('workspace_id', $workspaceId)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastOrder ? (int)substr($lastOrder->order_number, -6) + 1 : 1;
        return 'ORD' . date('Y') . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function searchOrders(string $query, int $workspaceId): Collection
    {
        return $this->orderRepository->searchOrders($query, $workspaceId);
    }

    public function getPendingOrders(int $workspaceId): Collection
    {
        return $this->orderRepository->getPendingOrders($workspaceId);
    }
}
