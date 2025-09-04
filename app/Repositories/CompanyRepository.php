<?php

namespace App\Repositories;

use App\Models\Company;
use App\Interfaces\CompanyInterface;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository implements CompanyInterface
{
    public function getAllCompanies(): Collection
    {
        return Company::with(['branches'])
            ->orderBy('name')
            ->get();
    }

    public function getCompanyById(int $id): ?Company
    {
        return Company::with(['branches', 'brands', 'units'])->find($id);
    }

    public function createCompany(array $data): Company
    {
        return Company::create($data);
    }

    public function updateCompany(int $id, array $data): bool
    {
        return Company::where('id', $id)->update($data);
    }

    public function deleteCompany(int $id): bool
    {
        return Company::destroy($id);
    }

    public function getActiveCompanies(): Collection
    {
        return Company::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function findBySlug(string $slug): ?Company
    {
        return Company::where('slug', $slug)->first();
    }

    public function getCompaniesWithBranchesCount(): Collection
    {
        return Company::withCount('branches')
            ->orderBy('name')
            ->get();
    }
}
