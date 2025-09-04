<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Interfaces\BrandInterface;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository implements BrandInterface
{
    public function getAllBrands(): Collection
    {
        return Brand::with(['company'])
            ->orderBy('name')
            ->get();
    }

    public function getBrandById(int $id): ?Brand
    {
        return Brand::with(['company', 'products'])->find($id);
    }

    public function createBrand(array $data): Brand
    {
        return Brand::create($data);
    }

    public function updateBrand(int $id, array $data): bool
    {
        return Brand::where('id', $id)->update($data);
    }

    public function deleteBrand(int $id): bool
    {
        return Brand::destroy($id);
    }

    public function getActiveBrands(): Collection
    {
        return Brand::where('is_active', true)
            ->with(['company'])
            ->orderBy('name')
            ->get();
    }

    public function getBrandsByCompany(int $companyId): Collection
    {
        return Brand::where('company_id', $companyId)
            ->orderBy('name')
            ->get();
    }

    public function findBySlug(string $slug): ?Brand
    {
        return Brand::where('slug', $slug)->first();
    }
}
