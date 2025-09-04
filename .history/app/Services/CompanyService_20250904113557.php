<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CompanyService
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository
    ) {}

    /**
     * Get all companies
     */
    public function getAllCompanies(): Collection
    {
        return $this->companyRepository->with(['branches'])->all();
    }

    /**
     * Get active companies
     */
    public function getActiveCompanies(): Collection
    {
        return $this->companyRepository->getActive();
    }

    /**
     * Create new company
     */
    public function createCompany(array $data): Company
    {
        // Generate slug if not provided
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure slug is unique
        $originalSlug = $data['slug'];
        $counter = 1;
        while ($this->companyRepository->findBySlug($data['slug'])) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $this->companyRepository->create($data);
    }

    /**
     * Update company
     */
    public function updateCompany(int $id, array $data): bool
    {
        $company = $this->companyRepository->findOrFail($id);

        // Update slug if name changed
        if (isset($data['name']) && $data['name'] !== $company->name) {
            $newSlug = Str::slug($data['name']);
            if ($newSlug !== $company->slug && !$this->companyRepository->findBySlug($newSlug)) {
                $data['slug'] = $newSlug;
            }
        }

        return $this->companyRepository->update($id, $data);
    }

    /**
     * Delete company
     */
    public function deleteCompany(int $id): bool
    {
        $company = $this->companyRepository->findOrFail($id);
        
        // Check if company has branches
        if ($company->branches()->exists()) {
            throw new \Exception('Cannot delete company with existing branches');
        }

        return $this->companyRepository->delete($id);
    }

    /**
     * Get company by ID
     */
    public function getCompanyById(int $id): Company
    {
        return $this->companyRepository->with(['branches', 'brands', 'units'])->findOrFail($id);
    }

    /**
     * Search companies
     */
    public function searchCompanies(string $query): Collection
    {
        return $this->companyRepository->searchByName($query);
    }

    /**
     * Get companies with statistics
     */
    public function getCompaniesWithStats(): Collection
    {
        return $this->companyRepository->getWithBranchesCount();
    }
}
