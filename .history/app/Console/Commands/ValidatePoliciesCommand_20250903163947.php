<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Team;
use App\Models\Workspace;

class ValidatePoliciesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'policies:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate that all models have proper policies and abstraction is maintained';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Validating Policy Coverage and Abstraction...');
        $this->line('');

        // Define all business models that should have policies
        $models = [
            'Category' => Category::class,
            'Customer' => Customer::class,
            'Product' => Product::class,
            'Project' => Project::class,
            'Task' => Task::class,
            'TimeEntry' => TimeEntry::class,
            'Expense' => Expense::class,
            'Order' => Order::class,
            'OrderItem' => OrderItem::class,
            'Invoice' => Invoice::class,
            'Payment' => Payment::class,
            'Team' => Team::class,
            'Workspace' => Workspace::class,
        ];

        // Standard policy methods that should exist
        $requiredMethods = ['viewAny', 'view', 'create', 'update', 'delete'];

        $this->validatePolicyRegistration($models);
        $this->line('');
        $this->validatePolicyMethods($models, $requiredMethods);
        $this->line('');
        $this->validateAbstraction();
        $this->line('');
        $this->validatePermissionCoverage();
        $this->line('');

        $this->info('✅ Policy validation completed!');
    }

    /**
     * Validate that all models have registered policies
     */
    private function validatePolicyRegistration(array $models)
    {
        $this->comment('📋 Checking Policy Registration...');

        foreach ($models as $name => $modelClass) {
            $policy = Gate::getPolicyFor($modelClass);

            if ($policy) {
                $this->info("  ✅ {$name}: " . get_class($policy));
            } else {
                $this->error("  ❌ {$name}: No policy registered");
            }
        }
    }

    /**
     * Validate that all policies have required methods
     */
    private function validatePolicyMethods(array $models, array $requiredMethods)
    {
        $this->comment('🔧 Checking Policy Methods...');

        foreach ($models as $name => $modelClass) {
            $policy = Gate::getPolicyFor($modelClass);

            if ($policy) {
                $missingMethods = [];
                foreach ($requiredMethods as $method) {
                    if (!method_exists($policy, $method)) {
                        $missingMethods[] = $method;
                    }
                }

                if (empty($missingMethods)) {
                    $this->info("  ✅ {$name}: All required methods present");
                } else {
                    $this->warn("  ⚠️  {$name}: Missing methods - " . implode(', ', $missingMethods));
                }
            }
        }
    }

    /**
     * Validate abstraction through trait usage
     */
    private function validateAbstraction()
    {
        $this->comment('🏗️  Checking Policy Abstraction...');

        $policyFiles = glob(app_path('Policies/*.php'));
        $traitUsage = 0;

        foreach ($policyFiles as $file) {
            $content = file_get_contents($file);
            $className = basename($file, '.php');

            if (strpos($content, 'use HasPolicyHelpers') !== false) {
                $this->info("  ✅ {$className}: Uses HasPolicyHelpers trait");
                $traitUsage++;
            } else {
                $this->warn("  ⚠️  {$className}: Not using HasPolicyHelpers trait");
            }
        }

        $this->info("  📊 Abstraction Usage: {$traitUsage}/" . count($policyFiles) . " policies use helpers");
    }

    /**
     * Validate permission coverage
     */
    private function validatePermissionCoverage()
    {
        $this->comment('🔑 Checking Permission Coverage...');

        // Check if all entities have CRUD permissions
        $entities = [
            'categories',
            'customers',
            'products',
            'projects',
            'tasks',
            'orders',
            'order-items',
            'invoices',
            'payments',
            'time-entries',
            'expenses',
            'users',
            'teams',
            'workspaces'
        ];

        $actions = ['view', 'create', 'update', 'delete'];

        foreach ($entities as $entity) {
            $permissions = [];
            foreach ($actions as $action) {
                $permissionName = "{$action}-{$entity}";
                if (\Spatie\Permission\Models\Permission::where('name', $permissionName)->exists()) {
                    $permissions[] = $action;
                }
            }

            if (count($permissions) === 4) {
                $this->info("  ✅ {$entity}: All CRUD permissions exist");
            } else {
                $missing = array_diff($actions, $permissions);
                $this->error("  ❌ {$entity}: Missing permissions - " . implode(', ', $missing));
            }
        }
    }
}
