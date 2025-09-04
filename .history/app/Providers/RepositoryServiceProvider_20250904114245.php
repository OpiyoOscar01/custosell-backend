<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Interfaces
use App\Interfaces\CompanyInterface;
use App\Interfaces\BranchInterface;
use App\Interfaces\BrandInterface;
use App\Interfaces\UnitInterface;

// Repositories
use App\Repositories\CompanyRepository;
use App\Repositories\BranchRepository;
use App\Repositories\BrandRepository;
use App\Repositories\UnitRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Company bindings
        $this->app->bind(CompanyInterface::class, CompanyRepository::class);
        
        // Branch bindings
        $this->app->bind(BranchInterface::class, BranchRepository::class);
        
        // Brand bindings
        $this->app->bind(BrandInterface::class, BrandRepository::class);
        
        // Unit bindings
        $this->app->bind(UnitInterface::class, UnitRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
