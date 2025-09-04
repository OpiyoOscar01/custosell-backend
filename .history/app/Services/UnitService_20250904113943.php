<?php

namespace App\Services;

use App\Models\Unit;
use App\Interfaces\UnitInterface;
use Illuminate\Database\Eloquent\Collection;

class UnitService
{
    protected $unitRepository;

    public function __construct(UnitInterface $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    public function getAllUnits(): Collection
    {
        return $this->unitRepository->getAllUnits();
    }

    public function getUnitById(int $id): ?Unit
    {
        return $this->unitRepository->getUnitById($id);
    }

    public function createUnit(array $data): Unit
    {
        return $this->unitRepository->createUnit($data);
    }

    public function updateUnit(int $id, array $data): bool
    {
        return $this->unitRepository->updateUnit($id, $data);
    }

    public function deleteUnit(int $id): bool
    {
        $unit = $this->unitRepository->getUnitById($id);
        
        if (!$unit) {
            return false;
        }
        
        // Check if unit has products
        if ($unit->products()->exists()) {
            throw new \Exception('Cannot delete unit with existing products');
        }

        return $this->unitRepository->deleteUnit($id);
    }

    public function getActiveUnits(): Collection
    {
        return $this->unitRepository->getActiveUnits();
    }

    public function getUnitsByCompany(int $companyId): Collection
    {
        return $this->unitRepository->getUnitsByCompany($companyId);
    }

    public function getUnitsByType(string $type): Collection
    {
        return $this->unitRepository->getUnitsByType($type);
    }
}
