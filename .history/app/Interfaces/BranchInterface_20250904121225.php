<?php

namespace App\Interfaces;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;

interface BranchInterface
{
    public function getAllBranches(): Collection;
    public function getBranchById(int $id): ?Branch;
    public function createBranch(array $data): Branch;
    public function updateBranch(int $id, array $data): bool;
    public function deleteBranch(int $id): bool;
    public function getActiveBranches(): Collection;
    public function getBranchesByCompany(int $companyId): Collection;
    public function findByCode(string $code): ?Branch;
    public function getBranchesManagedByUser(int $userId): Collection;
    public function getWarehouseBranches(): Collection;
    public function getPosEnabledBranches(): Collection;
}
