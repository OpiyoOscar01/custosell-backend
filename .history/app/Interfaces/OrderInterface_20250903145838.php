<?php

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderInterface
{
    public function getAllOrders(int $workspaceId): Collection;
    public function getOrderById(int $id): ?Order;
    public function createOrder(array $data): Order;
    public function updateOrder(int $id, array $data): bool;
    public function deleteOrder(int $id): bool;
    public function getOrdersByStatus(string $status, int $workspaceId): Collection;
    public function getOrdersByCustomer(int $customerId): Collection;
    public function getOrdersByDate(string $startDate, string $endDate, int $workspaceId): Collection;
    public function updateOrderStatus(int $id, string $status): bool;
}
