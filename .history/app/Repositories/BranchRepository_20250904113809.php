<?php

namespace App\Repositories;

use App\Models\Branch;
use App\Interfaces\BranchInterface;
use Illuminate\Database\Eloquent\Collection;

class BranchRepository implements BranchInterface
{
    public function getAllBranches(): Collection
    {
        return Branch::with(['company', 'manager'])
            ->orderBy('name')
            ->get();
    }

    public function getBranchById(int $id): ?Branch
    {
        return Branch::with(['company', 'manager', 'workspaces', 'products', 'customers'])->find($id);
    }

    public function createBranch(array $data): Branch
    {
        return Branch::create($data);
    }

    public function updateBranch(int $id, array $data): bool
    {
        return Branch::where('id', $id)->update($data);
    }

    public function deleteBranch(int $id): bool
    {
        return Branch::destroy($id);
    }

    public function getActiveBranches(): Collection
    {
        return Branch::where('is_active', true)
            ->with(['company'])
            ->orderBy('name')
            ->get();
    }

    public function getBranchesByCompany(int $companyId): Collection
    {
        return Branch::where('company_id', $companyId)
            ->with(['manager'])
            ->orderBy('name')
            ->get();
    }

    public function findByCode(string $code): ?Branch
    {
        return Branch::where('code', $code)->first();
    }

    public function getBranchesManagedByUser(int $userId): Collection
    {
        return Branch::where('manager_id', $userId)
            ->with(['company'])
            ->get();
    }

    public function getWarehouseBranches(): Collection
    {
        return Branch::where('is_warehouse', true)
            ->where('is_active', true)
            ->with(['company'])
            ->get();
    }

    public function getPosEnabledBranches(): Collection
    {
        return Branch::where('is_pos_enabled', true)
            ->where('is_active', true)
            ->with(['company'])
            ->get();
    }
}
