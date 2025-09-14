<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class AdvancedPermissionHelper
{
    /**
     * Check if user has ALL specified permissions
     */
    public static function hasAllPermissions(array $permissions): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        return collect($permissions)->every(function($permission) use ($user) {
            return $user->hasPermission($permission);
        });
    }

    /**
     * Check if user has ANY of the specified permissions
     */
    public static function hasAnyPermission(array $permissions): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        return collect($permissions)->contains(function($permission) use ($user) {
            return $user->hasPermission($permission);
        });
    }

    /**
     * Check if user has specific permission level
     */
    public static function hasPermissionLevel(string $module, string $level): bool
    {
        $permissionMap = [
            'view' => ['view'],
            'create' => ['view', 'create'],
            'edit' => ['view', 'create', 'edit'],
            'delete' => ['view', 'create', 'edit', 'delete'],
            'admin' => ['view', 'create', 'edit', 'delete', 'admin']
        ];

        $requiredPermissions = $permissionMap[$level] ?? [];
        $permissions = collect($requiredPermissions)->map(function($perm) use ($module) {
            return $module . '.' . $perm;
        })->toArray();

        return self::hasAllPermissions($permissions);
    }

    /**
     * Get user's permission level for a module
     */
    public static function getPermissionLevel(string $module): string
    {
        $levels = ['admin', 'delete', 'edit', 'create', 'view'];
        
        foreach ($levels as $level) {
            if (self::hasPermissionLevel($module, $level)) {
                return $level;
            }
        }
        
        return 'none';
    }

    /**
     * Check if user can perform action based on module and action
     */
    public static function canPerformAction(string $module, string $action): bool
    {
        $permission = $module . '.' . $action;
        return self::hasPermission($permission);
    }

    /**
     * Get user's accessible actions for a module
     */
    public static function getAccessibleActions(string $module): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        $user = Auth::user();
        $allActions = ['view', 'create', 'edit', 'delete', 'export', 'import', 'admin'];
        
        return collect($allActions)->filter(function($action) use ($user, $module) {
            return $user->hasPermission($module . '.' . $action);
        })->toArray();
    }

    /**
     * Check if user has role-based permissions
     */
    public static function hasRoleBasedPermission(string $role, string $permission): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        return $user->hasRole($role) && $user->hasPermission($permission);
    }

    /**
     * Get user's permission matrix
     */
    public static function getPermissionMatrix(): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        $user = Auth::user();
        $modules = ['users', 'products', 'orders', 'categories', 'reports', 'settings'];
        $actions = ['view', 'create', 'edit', 'delete', 'export', 'import'];
        
        $matrix = [];
        
        foreach ($modules as $module) {
            $matrix[$module] = [];
            foreach ($actions as $action) {
                $permission = $module . '.' . $action;
                $matrix[$module][$action] = $user->hasPermission($permission);
            }
        }
        
        return $matrix;
    }

    /**
     * Check if user can access feature based on multiple conditions
     */
    public static function canAccessFeature(array $conditions): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        foreach ($conditions as $condition) {
            switch ($condition['type']) {
                case 'permission':
                    if (!$user->hasPermission($condition['value'])) {
                        return false;
                    }
                    break;
                    
                case 'role':
                    if (!$user->hasRole($condition['value'])) {
                        return false;
                    }
                    break;
                    
                case 'any_permission':
                    if (!$user->hasAnyPermission($condition['value'])) {
                        return false;
                    }
                    break;
                    
                case 'all_permissions':
                    if (!self::hasAllPermissions($condition['value'])) {
                        return false;
                    }
                    break;
                    
                case 'custom':
                    if (!call_user_func($condition['callback'], $user)) {
                        return false;
                    }
                    break;
            }
        }
        
        return true;
    }

    /**
     * Get user's menu permissions
     */
    public static function getMenuPermissions(): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        $user = Auth::user();
        
        return [
            'dashboard' => [
                'can_view' => $user->hasPermission('dashboard.view'),
                'can_manage' => $user->hasPermission('dashboard.manage')
            ],
            'products' => [
                'can_view' => $user->hasPermission('products.view'),
                'can_create' => $user->hasPermission('products.create'),
                'can_edit' => $user->hasPermission('products.edit'),
                'can_delete' => $user->hasPermission('products.delete'),
                'can_export' => $user->hasPermission('products.export'),
                'can_import' => $user->hasPermission('products.import'),
                'level' => self::getPermissionLevel('products')
            ],
            'users' => [
                'can_view' => $user->hasPermission('users.view'),
                'can_create' => $user->hasPermission('users.create'),
                'can_edit' => $user->hasPermission('users.edit'),
                'can_delete' => $user->hasPermission('users.delete'),
                'level' => self::getPermissionLevel('users')
            ],
            'orders' => [
                'can_view' => $user->hasPermission('orders.view'),
                'can_create' => $user->hasPermission('orders.create'),
                'can_edit' => $user->hasPermission('orders.edit'),
                'can_delete' => $user->hasPermission('orders.delete'),
                'can_process' => $user->hasPermission('orders.process'),
                'level' => self::getPermissionLevel('orders')
            ]
        ];
    }

    /**
     * Check if user can access admin panel
     */
    public static function canAccessAdmin(): bool
    {
        $adminPermissions = [
            'dashboard.view',
            'users.view',
            'products.view',
            'orders.view'
        ];
        
        return self::hasAnyPermission($adminPermissions);
    }

    /**
     * Get user's accessible modules with their permission levels
     */
    public static function getAccessibleModules(): array
    {
        if (!Auth::check()) {
            return [];
        }
        
        $modules = ['users', 'products', 'orders', 'categories', 'reports', 'settings'];
        $accessibleModules = [];
        
        foreach ($modules as $module) {
            $level = self::getPermissionLevel($module);
            if ($level !== 'none') {
                $accessibleModules[$module] = [
                    'level' => $level,
                    'actions' => self::getAccessibleActions($module)
                ];
            }
        }
        
        return $accessibleModules;
    }
}