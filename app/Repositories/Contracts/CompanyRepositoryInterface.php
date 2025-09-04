<?php

namespace App\Repositories\Contracts;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

interface CompanyRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get active companies
     */
    public function getActive(): Collection;

    /**
     * Find company by slug
     */
    public function findBySlug(string $slug): ?Company;

    /**
     * Get companies with branches count
     */
    public function getWithBranchesCount(): Collection;

    /**
     * Search companies by name or legal name
     */
    public function searchByName(string $query): Collection;
}
