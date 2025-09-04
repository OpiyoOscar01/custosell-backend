<?php

namespace App\Interfaces;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

interface CompanyInterface
{
    public function getAllCompanies(): Collection;
    public function getCompanyById(int $id): ?Company;
    public function createCompany(array $data): Company;
    public function updateCompany(int $id, array $data): bool;
    public function deleteCompany(int $id): bool;
    public function getActiveCompanies(): Collection;
    public function findBySlug(string $slug): ?Company;
    public function getCompaniesWithBranchesCount(): Collection;
}
