<?php

namespace App\Services;

use App\Models\Company;
use App\Interfaces\CompanyInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CompanyService
{
    protected $companyRepository;

    public function __construct(CompanyInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function getAllCompanies(): Collection
    {
        return $this->companyRepository->getAllCompanies();
    }

    public function getCompanyById(int $id): ?Company
    {
        return $this->companyRepository->getCompanyById($id);
    }

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

        return $this->companyRepository->createCompany($data);
    }

    public function updateCompany(int $id, array $data): bool
    {
        $company = $this->companyRepository->getCompanyById($id);
        
        if (!$company) {
            return false;
        }

        // Update slug if name changed
        if (isset($data['name']) && $data['name'] !== $company->name) {
            $newSlug = Str::slug($data['name']);
            if ($newSlug !== $company->slug && !$this->companyRepository->findBySlug($newSlug)) {
                $data['slug'] = $newSlug;
            }
        }

        return $this->companyRepository->updateCompany($id, $data);
    }

    public function deleteCompany(int $id): bool
    {
        $company = $this->companyRepository->getCompanyById($id);
        
        if (!$company) {
            return false;
        }
        
        // Check if company has branches
        if ($company->branches()->exists()) {
            throw new \Exception('Cannot delete company with existing branches');
        }

        return $this->companyRepository->deleteCompany($id);
    }

    public function getActiveCompanies(): Collection
    {
        return $this->companyRepository->getActiveCompanies();
    }

    public function searchCompanies(string $query): Collection
    {
        return $this->companyRepository->getAllCompanies()->filter(function ($company) use ($query) {
            return stripos($company->name, $query) !== false || 
                   stripos($company->legal_name, $query) !== false;
        });
    }

    public function getCompaniesWithStats(): Collection
    {
        return $this->companyRepository->getCompaniesWithBranchesCount();
    }
}
