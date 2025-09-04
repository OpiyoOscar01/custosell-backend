<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get orders by workspace
     */
    public function getByWorkspace(int $workspaceId): Collection;

    /**
     * Get orders by branch
     */
    public function getByBranch(int $branchId): Collection;

    /**
     * Get orders by customer
     */
    public function getByCustomer(int $customerId): Collection;

    /**
     * Get orders by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Find order by number
     */
    public function findByNumber(string $orderNumber): ?Order;

    /**
     * Get pending orders
     */
    public function getPending(): Collection;

    /**
     * Get orders for date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;

    /**
     * Calculate total revenue
     */
    public function calculateRevenue(int $workspaceId, ?string $startDate = null, ?string $endDate = null): float;

    /**
     * Get top selling products
     */
    public function getTopSellingProducts(int $workspaceId, int $limit = 10): Collection;

    /**
     * Update order status
     */
    public function updateStatus(int $orderId, string $status): bool;

    /**
     * Generate order number
     */
    public function generateOrderNumber(): string;
}
