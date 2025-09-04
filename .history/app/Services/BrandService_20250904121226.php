<?php

namespace App\Services;

use App\Models\Brand;
use App\Interfaces\BrandInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BrandService
{
    protected $brandRepository;

    public function __construct(BrandInterface $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function getAllBrands(): Collection
    {
        return $this->brandRepository->getAllBrands();
    }

    public function getBrandById(int $id): ?Brand
    {
        return $this->brandRepository->getBrandById($id);
    }

    public function createBrand(array $data): Brand
    {
        // Generate slug if not provided
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure slug is unique
        $originalSlug = $data['slug'];
        $counter = 1;
        while ($this->brandRepository->findBySlug($data['slug'])) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $this->brandRepository->createBrand($data);
    }

    public function updateBrand(int $id, array $data): bool
    {
        $brand = $this->brandRepository->getBrandById($id);

        if (!$brand) {
            return false;
        }

        // Update slug if name changed
        if (isset($data['name']) && $data['name'] !== $brand->name) {
            $newSlug = Str::slug($data['name']);
            if ($newSlug !== $brand->slug && !$this->brandRepository->findBySlug($newSlug)) {
                $data['slug'] = $newSlug;
            }
        }

        return $this->brandRepository->updateBrand($id, $data);
    }

    public function deleteBrand(int $id): bool
    {
        $brand = $this->brandRepository->getBrandById($id);

        if (!$brand) {
            return false;
        }

        // Check if brand has products
        if ($brand->products()->exists()) {
            throw new \Exception('Cannot delete brand with existing products');
        }

        return $this->brandRepository->deleteBrand($id);
    }

    public function getActiveBrands(): Collection
    {
        return $this->brandRepository->getActiveBrands();
    }

    public function getBrandsByCompany(int $companyId): Collection
    {
        return $this->brandRepository->getBrandsByCompany($companyId);
    }
}
