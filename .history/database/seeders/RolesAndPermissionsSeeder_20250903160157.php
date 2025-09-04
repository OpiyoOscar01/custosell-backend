<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define entities
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
            'users'
        ];

        // Create permissions for each entity
        $actions = ['view', 'create', 'update', 'delete'];
        
        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}-{$entity}",
                    'guard_name' => 'web'
                ]);
            }
        }

        // Additional specific permissions
        $additionalPermissions = [
            'view-reports',
            'export-data',
            'manage-settings',
            'view-dashboard',
            'assign-tasks',
            'approve-expenses',
            'process-payments',
            'manage-roles',
            'bulk-actions'
        ];

        foreach ($additionalPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        $managerRole = Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'web'
        ]);
        $employeeRole = Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'web'
        ]);
        $clientRole = Role::firstOrCreate([
            'name' => 'client',
            'guard_name' => 'web'
        ]);

        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());

        // Manager permissions
        $managerPermissions = [
            // Full access to business entities
            'view-categories', 'create-categories', 'update-categories', 'delete-categories',
            'view-customers', 'create-customers', 'update-customers', 'delete-customers',
            'view-products', 'create-products', 'update-products', 'delete-products',
            'view-projects', 'create-projects', 'update-projects', 'delete-projects',
            'view-tasks', 'create-tasks', 'update-tasks', 'delete-tasks',
            'view-orders', 'create-orders', 'update-orders', 'delete-orders',
            'view-order-items', 'create-order-items', 'update-order-items', 'delete-order-items',
            'view-invoices', 'create-invoices', 'update-invoices', 'delete-invoices',
            'view-payments', 'create-payments', 'update-payments', 'delete-payments',
            'view-time-entries', 'create-time-entries', 'update-time-entries', 'delete-time-entries',
            'view-expenses', 'create-expenses', 'update-expenses', 'delete-expenses',
            'view-users', 'create-users', 'update-users',
            // Additional permissions
            'view-reports', 'export-data', 'view-dashboard', 'assign-tasks', 
            'approve-expenses', 'process-payments', 'bulk-actions'
        ];
        $managerRole->givePermissionTo($managerPermissions);

        // Employee permissions
        $employeePermissions = [
            // View access to most entities
            'view-categories', 'view-customers', 'view-products', 'view-projects',
            'view-tasks', 'update-tasks', 'view-orders', 'view-invoices',
            // Manage own entries
            'view-time-entries', 'create-time-entries', 'update-time-entries',
            'view-expenses', 'create-expenses', 'update-expenses',
            // Limited access
            'view-dashboard'
        ];
        $employeeRole->givePermissionTo($employeePermissions);

        // Client permissions (very limited)
        $clientPermissions = [
            'view-projects', 'view-tasks', 'view-invoices', 'view-orders'
        ];
        $clientRole->givePermissionTo($clientPermissions);
    }
}
