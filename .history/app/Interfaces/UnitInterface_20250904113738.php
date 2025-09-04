<?php

namespace App\Interfaces;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;

interface UnitInterface
{
    public function getAllUnits(): Collection;
    public function getUnitById(int $id): ?Unit;
    public function createUnit(array $data): Unit;
    public function updateUnit(int $id, array $data): bool;
    public function deleteUnit(int $id): bool;
    public function getActiveUnits(): Collection;
    public function getUnitsByCompany(int $companyId): Collection;
    public function getUnitsByType(string $type): Collection;
}
