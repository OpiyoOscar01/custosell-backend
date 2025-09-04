<?php

namespace App\Repositories\Contracts;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;

interface BranchRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get active branches
     */
    public function getActive(): Collection;

    /**
     * Get branches by company
     */
    public function getByCompany(int $companyId): Collection;

    /**
     * Find branch by code
     */
    public function findByCode(string $code): ?Branch;

    /**
     * Get branches managed by user
     */
    public function getManagedByUser(int $userId): Collection;

    /**
     * Get warehouse branches
     */
    public function getWarehouses(): Collection;

    /**
     * Get POS enabled branches
     */
    public function getPosEnabled(): Collection;
}
