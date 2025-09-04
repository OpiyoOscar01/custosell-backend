<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    /**
     * Get orders by workspace
     */
    public function getByWorkspace(int $workspaceId): Collection
    {
        return $this->model->where('workspace_id', $workspaceId)->get();
    }

    /**
     * Get orders by branch
     */
    public function getByBranch(int $branchId): Collection
    {
        return $this->model->where('branch_id', $branchId)->get();
    }

    /**
     * Get orders by customer
     */
    public function getByCustomer(int $customerId): Collection
    {
        return $this->model->where('customer_id', $customerId)->get();
    }

    /**
     * Get orders by status
     */
    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Find order by number
     */
    public function findByNumber(string $orderNumber): ?Order
    {
        return $this->model->where('order_number', $orderNumber)->first();
    }

    /**
     * Get pending orders
     */
    public function getPending(): Collection
    {
        return $this->model->whereIn('status', ['draft', 'pending', 'confirmed'])->get();
    }

    /**
     * Get orders for date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model->whereBetween('order_date', [$startDate, $endDate])->get();
    }

    /**
     * Calculate total revenue
     */
    public function calculateRevenue(int $workspaceId, ?string $startDate = null, ?string $endDate = null): float
    {
        $query = $this->model->where('workspace_id', $workspaceId)
            ->where('status', 'delivered');

        if ($startDate && $endDate) {
            $query->whereBetween('order_date', [$startDate, $endDate]);
        }

        return $query->sum('total_amount');
    }

    /**
     * Get top selling products
     */
    public function getTopSellingProducts(int $workspaceId, int $limit = 10): Collection
    {
        return DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.workspace_id', $workspaceId)
            ->where('orders.status', 'delivered')
            ->select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Update order status
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        return $this->model->where('id', $orderId)->update(['status' => $status]);
    }

    /**
     * Generate order number
     */
    public function generateOrderNumber(): string
    {
        $year = date('Y');
        $lastOrder = $this->model->where('order_number', 'like', "ORD-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -6));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('ORD-%s-%06d', $year, $newNumber);
    }
}
