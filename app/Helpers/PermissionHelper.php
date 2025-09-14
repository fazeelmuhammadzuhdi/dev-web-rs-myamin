<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if current user has permission
     */
    public static function hasPermission(string $permission): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->hasPermission($permission);
    }

    /**
     * Check if current user has any of the given permissions
     */
    public static function hasAnyPermission(array $permissions): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->hasAnyPermission($permissions);
    }

    /**
     * Check if current user has role
     */
    public static function hasRole(string $role): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->hasRole($role);
    }

    /**
     * Check if current user has any of the given roles
     */
    public static function hasAnyRole(array $roles): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->hasAnyRole($roles);
    }

    /**
     * Check if current user is admin
     */
    public static function isAdmin(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Auth::user()->isAdmin();
    }

    /**
     * Get current user's permissions
     */
    public static function getUserPermissions()
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return Auth::user()->getActivePermissions();
    }

    /**
     * Get current user's roles
     */
    public static function getUserRoles()
    {
        if (!Auth::check()) {
            return collect();
        }
        
        return Auth::user()->getActiveRoles();
    }

    /**
     * Check if user can access resource
     */
    public static function canAccess(string $resource, string $action = 'view'): bool
    {
        $permission = $resource . '.' . $action;
        return self::hasPermission($permission);
    }

    /**
     * Get permission-based menu items
     */
    public static function getMenuItems(): array
    {
        $menuItems = [
            [
                'title' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'url' => route('admin.dashboard'),
                'permission' => 'dashboard.view'
            ],
            [
                'title' => 'Products',
                'icon' => 'fas fa-boxes',
                'url' => route('admin.products.index'),
                'permission' => 'products.view',
                'children' => [
                    [
                        'title' => 'All Products',
                        'url' => route('admin.products.index'),
                        'permission' => 'products.view'
                    ],
                    [
                        'title' => 'Add Product',
                        'url' => route('admin.products.create'),
                        'permission' => 'products.create'
                    ]
                ]
            ],
            [
                'title' => 'User Management',
                'icon' => 'fas fa-users',
                'permission' => 'users.view',
                'children' => [
                    [
                        'title' => 'All Users',
                        'url' => route('admin.users.index'),
                        'permission' => 'users.view'
                    ],
                    [
                        'title' => 'Roles',
                        'url' => route('admin.roles.index'),
                        'permission' => 'roles.view'
                    ],
                    [
                        'title' => 'User Roles',
                        'url' => route('admin.user-roles.index'),
                        'permission' => 'user-roles.view'
                    ]
                ]
            ],
            [
                'title' => 'Reports',
                'icon' => 'fas fa-chart-bar',
                'url' => route('admin.reports.index'),
                'permission' => 'reports.view'
            ],
            [
                'title' => 'Settings',
                'icon' => 'fas fa-cog',
                'url' => route('admin.settings.index'),
                'permission' => 'settings.view'
            ]
        ];

        return self::filterMenuByPermissions($menuItems);
    }

    /**
     * Filter menu items by user permissions
     */
    private static function filterMenuByPermissions(array $menuItems): array
    {
        $filteredItems = [];

        foreach ($menuItems as $item) {
            // Check if user has permission for this menu item
            if (isset($item['permission']) && !self::hasPermission($item['permission'])) {
                continue;
            }

            // Filter children if they exist
            if (isset($item['children'])) {
                $filteredChildren = self::filterMenuByPermissions($item['children']);
                if (!empty($filteredChildren)) {
                    $item['children'] = $filteredChildren;
                    $filteredItems[] = $item;
                }
            } else {
                $filteredItems[] = $item;
            }
        }

        return $filteredItems;
    }

    /**
     * Generate permission-based buttons
     */
    public static function generateActionButtons(array $actions): string
    {
        $buttons = '';

        foreach ($actions as $action) {
            if (isset($action['permission']) && !self::hasPermission($action['permission'])) {
                continue;
            }

            $buttons .= '<button class="btn btn-sm ' . $action['class'] . ' mr-1" ';
            $buttons .= 'onclick="' . $action['onclick'] . '" title="' . $action['title'] . '">';
            $buttons .= '<i class="' . $action['icon'] . '"></i>';
            $buttons .= '</button>';
        }

        return $buttons;
    }

    /**
     * Check if user can perform action on resource
     */
    public static function canPerformAction(string $resource, string $action): bool
    {
        $permission = $resource . '.' . $action;
        return self::hasPermission($permission);
    }

    /**
     * Get user's accessible modules
     */
    public static function getAccessibleModules(): array
    {
        $permissions = self::getUserPermissions();
        $modules = $permissions->pluck('module')->unique()->filter()->toArray();
        
        return array_values($modules);
    }

    /**
     * Check if user can access module
     */
    public static function canAccessModule(string $module): bool
    {
        $accessibleModules = self::getAccessibleModules();
        return in_array($module, $accessibleModules);
    }
}