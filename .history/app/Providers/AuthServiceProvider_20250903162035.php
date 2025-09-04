<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Policies\CategoryPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\TimeEntryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Customer::class => CustomerPolicy::class,
        Expense::class => ExpensePolicy::class,
        Product::class => ProductPolicy::class,
        Project::class => ProjectPolicy::class,
        Task::class => TaskPolicy::class,
        TimeEntry::class => TimeEntryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define custom gates if needed
        Gate::define('view-dashboard', function ($user) {
            return $user->can('view-dashboard');
        });

        Gate::define('view-reports', function ($user) {
            return $user->can('view-reports');
        });

        Gate::define('manage-settings', function ($user) {
            return $user->can('manage-settings');
        });

        Gate::define('bulk-actions', function ($user) {
            return $user->can('bulk-actions');
        });

        // Super admin gate - bypasses all policy checks
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });
    }
}
