<?php

namespace App\Repositories;

use App\Models\Order;
use App\Interfaces\OrderInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderInterface
{
    public function getAllOrders(int $workspaceId): Collection
    {
        return Order::where('workspace_id', $workspaceId)
            ->with(['customer', 'creator', 'orderItems'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOrderById(int $id): ?Order
    {
        return Order::with(['customer', 'creator', 'orderItems.product', 'invoices', 'payments'])
            ->find($id);
    }

    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }

    public function updateOrder(int $id, array $data): bool
    {
        return Order::where('id', $id)->update($data);
    }

    public function deleteOrder(int $id): bool
    {
        return Order::destroy($id);
    }

    public function getOrdersByStatus(string $status, int $workspaceId): Collection
    {
        return Order::where('workspace_id', $workspaceId)
            ->where('status', $status)
            ->with(['customer', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOrdersByCustomer(int $customerId): Collection
    {
        return Order::where('customer_id', $customerId)
            ->with(['creator', 'orderItems'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOrdersByDate(string $startDate, string $endDate, int $workspaceId): Collection
    {
        return Order::where('workspace_id', $workspaceId)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->with(['customer', 'creator'])
            ->orderBy('order_date', 'desc')
            ->get();
    }

    public function updateOrderStatus(int $id, string $status): bool
    {
        return Order::where('id', $id)->update(['status' => $status]);
    }
}
