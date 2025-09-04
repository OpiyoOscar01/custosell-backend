<?php

namespace App\Repositories\Eloquent;

use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    public function __construct(Company $model)
    {
        parent::__construct($model);
    }

    /**
     * Get active companies
     */
    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }

    /**
     * Find company by slug
     */
    public function findBySlug(string $slug): ?Company
    {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * Get companies with branches count
     */
    public function getWithBranchesCount(): Collection
    {
        return $this->model->withCount('branches')->get();
    }

    /**
     * Search companies by name or legal name
     */
    public function searchByName(string $query): Collection
    {
        return $this->model->where('name', 'like', "%{$query}%")
            ->orWhere('legal_name', 'like', "%{$query}%")
            ->get();
    }
}
