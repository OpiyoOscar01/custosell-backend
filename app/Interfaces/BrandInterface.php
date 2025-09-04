<?php

namespace App\Interfaces;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

interface BrandInterface
{
    public function getAllBrands(): Collection;
    public function getBrandById(int $id): ?Brand;
    public function createBrand(array $data): Brand;
    public function updateBrand(int $id, array $data): bool;
    public function deleteBrand(int $id): bool;
    public function getActiveBrands(): Collection;
    public function getBrandsByCompany(int $companyId): Collection;
    public function findBySlug(string $slug): ?Brand;
}
