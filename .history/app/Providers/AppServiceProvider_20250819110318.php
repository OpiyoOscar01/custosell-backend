<?php

namespace App\Providers;

use App\Interfaces\Auth\AuthInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Auth\AuthRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
