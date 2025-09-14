<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'users.view', 'module' => 'users'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'module' => 'users'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'module' => 'users'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'module' => 'users'],
            
            // Role Management
            ['name' => 'View Roles', 'slug' => 'roles.view', 'module' => 'roles'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'module' => 'roles'],
            ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'module' => 'roles'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'module' => 'roles'],
            
            // User Role Management
            ['name' => 'View User Roles', 'slug' => 'user-roles.view', 'module' => 'user-roles'],
            ['name' => 'Assign User Roles', 'slug' => 'user-roles.assign', 'module' => 'user-roles'],
            ['name' => 'Revoke User Roles', 'slug' => 'user-roles.revoke', 'module' => 'user-roles'],
            
            // Product Management
            ['name' => 'View Products', 'slug' => 'products.view', 'module' => 'products'],
            ['name' => 'Create Products', 'slug' => 'products.create', 'module' => 'products'],
            ['name' => 'Edit Products', 'slug' => 'products.edit', 'module' => 'products'],
            ['name' => 'Delete Products', 'slug' => 'products.delete', 'module' => 'products'],
            ['name' => 'Export Products', 'slug' => 'products.export', 'module' => 'products'],
            
            // Order Management
            ['name' => 'View Orders', 'slug' => 'orders.view', 'module' => 'orders'],
            ['name' => 'Create Orders', 'slug' => 'orders.create', 'module' => 'orders'],
            ['name' => 'Edit Orders', 'slug' => 'orders.edit', 'module' => 'orders'],
            ['name' => 'Delete Orders', 'slug' => 'orders.delete', 'module' => 'orders'],
            ['name' => 'Process Orders', 'slug' => 'orders.process', 'module' => 'orders'],
            
            // Category Management
            ['name' => 'View Categories', 'slug' => 'categories.view', 'module' => 'categories'],
            ['name' => 'Create Categories', 'slug' => 'categories.create', 'module' => 'categories'],
            ['name' => 'Edit Categories', 'slug' => 'categories.edit', 'module' => 'categories'],
            ['name' => 'Delete Categories', 'slug' => 'categories.delete', 'module' => 'categories'],
            
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view', 'module' => 'dashboard'],
            ['name' => 'View Statistics', 'slug' => 'statistics.view', 'module' => 'dashboard'],
            
            // Settings
            ['name' => 'View Settings', 'slug' => 'settings.view', 'module' => 'settings'],
            ['name' => 'Edit Settings', 'slug' => 'settings.edit', 'module' => 'settings'],
            
            // Reports
            ['name' => 'View Reports', 'slug' => 'reports.view', 'module' => 'reports'],
            ['name' => 'Generate Reports', 'slug' => 'reports.generate', 'module' => 'reports'],
            ['name' => 'Export Reports', 'slug' => 'reports.export', 'module' => 'reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Create roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full system access with all permissions',
                'permissions' => Permission::all()->pluck('id')->toArray()
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrative access to most features',
                'permissions' => Permission::whereNotIn('slug', [
                    'users.delete',
                    'roles.delete',
                    'settings.edit'
                ])->pluck('id')->toArray()
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Management access to products and orders',
                'permissions' => Permission::whereIn('module', [
                    'products',
                    'orders',
                    'categories',
                    'dashboard',
                    'reports'
                ])->pluck('id')->toArray()
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'description' => 'Content editing and product management',
                'permissions' => Permission::whereIn('slug', [
                    'products.view',
                    'products.create',
                    'products.edit',
                    'categories.view',
                    'categories.create',
                    'categories.edit',
                    'dashboard.view',
                    'statistics.view'
                ])->pluck('id')->toArray()
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'Read-only access to most features',
                'permissions' => Permission::whereIn('slug', [
                    'products.view',
                    'orders.view',
                    'categories.view',
                    'users.view',
                    'dashboard.view',
                    'statistics.view',
                    'reports.view'
                ])->pluck('id')->toArray()
            ],
            [
                'name' => 'Customer',
                'slug' => 'customer',
                'description' => 'Basic customer access',
                'permissions' => Permission::whereIn('slug', [
                    'products.view',
                    'orders.view',
                    'orders.create'
                ])->pluck('id')->toArray()
            ]
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
            
            // Assign permissions to role
            $role->syncPermissions($permissions);
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }
}