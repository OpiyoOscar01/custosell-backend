<?php

namespace App\Services;

use App\Models\Branch;
use App\Interfaces\BranchInterface;
use Illuminate\Database\Eloquent\Collection;

class BranchService
{
    protected $branchRepository;

    public function __construct(BranchInterface $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function getAllBranches(): Collection
    {
        return $this->branchRepository->getAllBranches();
    }

    public function getBranchById(int $id): ?Branch
    {
        return $this->branchRepository->getBranchById($id);
    }

    public function createBranch(array $data): Branch
    {
        // Generate branch code if not provided
        if (!isset($data['code'])) {
            $data['code'] = $this->generateBranchCode($data['company_id']);
        }

        // Ensure code is unique
        $originalCode = $data['code'];
        $counter = 1;
        while ($this->branchRepository->findByCode($data['code'])) {
            $data['code'] = $originalCode . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $counter++;
        }

        return $this->branchRepository->createBranch($data);
    }

    public function updateBranch(int $id, array $data): bool
    {
        return $this->branchRepository->updateBranch($id, $data);
    }

    public function deleteBranch(int $id): bool
    {
        $branch = $this->branchRepository->getBranchById($id);
        
        if (!$branch) {
            return false;
        }
        
        // Check if branch has workspaces or products
        if ($branch->workspaces()->exists() || $branch->products()->exists()) {
            throw new \Exception('Cannot delete branch with existing workspaces or products');
        }

        return $this->branchRepository->deleteBranch($id);
    }

    public function getActiveBranches(): Collection
    {
        return $this->branchRepository->getActiveBranches();
    }

    public function getBranchesByCompany(int $companyId): Collection
    {
        return $this->branchRepository->getBranchesByCompany($companyId);
    }

    public function getBranchesManagedByUser(int $userId): Collection
    {
        return $this->branchRepository->getBranchesManagedByUser($userId);
    }

    public function getWarehouseBranches(): Collection
    {
        return $this->branchRepository->getWarehouseBranches();
    }

    public function getPosEnabledBranches(): Collection
    {
        return $this->branchRepository->getPosEnabledBranches();
    }

    private function generateBranchCode(int $companyId): string
    {
        $branches = $this->branchRepository->getBranchesByCompany($companyId);
        $count = $branches->count() + 1;
        return 'BR' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
