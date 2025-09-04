<?php

namespace App\Repositories;

use App\Models\Unit;
use App\Interfaces\UnitInterface;
use Illuminate\Database\Eloquent\Collection;

class UnitRepository implements UnitInterface
{
    public function getAllUnits(): Collection
    {
        return Unit::with(['company'])
            ->orderBy('name')
            ->get();
    }

    public function getUnitById(int $id): ?Unit
    {
        return Unit::with(['company', 'products'])->find($id);
    }

    public function createUnit(array $data): Unit
    {
        return Unit::create($data);
    }

    public function updateUnit(int $id, array $data): bool
    {
        return Unit::where('id', $id)->update($data);
    }

    public function deleteUnit(int $id): bool
    {
        return Unit::destroy($id);
    }

    public function getActiveUnits(): Collection
    {
        return Unit::where('is_active', true)
            ->with(['company'])
            ->orderBy('name')
            ->get();
    }

    public function getUnitsByCompany(int $companyId): Collection
    {
        return Unit::where('company_id', $companyId)
            ->orderBy('name')
            ->get();
    }

    public function getUnitsByType(string $type): Collection
    {
        return Unit::where('type', $type)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
