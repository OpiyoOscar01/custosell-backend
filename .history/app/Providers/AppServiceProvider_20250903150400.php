<?php

namespace App\Providers;

use App\Interfaces\Auth\AuthInterface;
use App\Interfaces\CategoryInterface;
use App\Interfaces\CustomerInterface;
use App\Interfaces\ProductInterface;
use App\Interfaces\ProjectInterface;
use App\Interfaces\TaskInterface;
use App\Interfaces\OrderInterface;
use App\Interfaces\InvoiceInterface;
use App\Interfaces\PaymentInterface;
use App\Interfaces\ExpenseInterface;
use App\Interfaces\TimeEntryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Repositories\OrderRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\TimeEntryRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthInterface::class, AuthRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
