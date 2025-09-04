<?php

namespace App\Repositories\Eloquent;

use App\Models\Branch;
use App\Repositories\Contracts\BranchRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BranchRepository extends BaseRepository implements BranchRepositoryInterface
{
    public function __construct(Branch $model)
    {
        parent::__construct($model);
    }

    /**
     * Get active branches
     */
    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    /**
     * Get branches by company
     */
    public function getByCompany(int $companyId): Collection
    {
        return $this->model->where('company_id', $companyId)->get();
    }

    /**
     * Find branch by code
     */
    public function findByCode(string $code): ?Branch
    {
        return $this->model->where('code', $code)->first();
    }

    /**
     * Get branches managed by user
     */
    public function getManagedByUser(int $userId): Collection
    {
        return $this->model->where('manager_id', $userId)->get();
    }

    /**
     * Get warehouse branches
     */
    public function getWarehouses(): Collection
    {
        return $this->model->where('is_warehouse', true)->active()->get();
    }

    /**
     * Get POS enabled branches
     */
    public function getPosEnabled(): Collection
    {
        return $this->model->where('is_pos_enabled', true)->active()->get();
    }
}
