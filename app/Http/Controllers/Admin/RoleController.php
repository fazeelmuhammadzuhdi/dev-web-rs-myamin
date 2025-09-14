<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:roles.view')->only(['index', 'show']);
        $this->middleware('permission:roles.create')->only(['create', 'store']);
        $this->middleware('permission:roles.edit')->only(['edit', 'update']);
        $this->middleware('permission:roles.delete')->only(['destroy']);
    }

    /**
     * Display a listing of roles
     */
    public function index()
    {
        $permissions = Permission::getGroupedByModule();
        
        return view('admin.roles.index', compact('permissions'));
    }

    /**
     * Get data for DataTables
     */
    public function getData(Request $request)
    {
        try {
            $query = Role::with(['permissions', 'users']);

            return DataTables::of($query)
                ->addColumn('permissions_count', function ($role) {
                    return $role->permissions->count();
                })
                ->addColumn('users_count', function ($role) {
                    return $role->users->count();
                })
                ->addColumn('status', function ($role) {
                    return $this->formatStatusBadge($role->is_active);
                })
                ->addColumn('permissions', function ($role) {
                    return $this->formatPermissionsList($role->permissions);
                })
                ->addColumn('actions', function ($role) {
                    return $this->formatActionButtons($role);
                })
                ->rawColumns(['status', 'permissions', 'actions'])
                ->make(true);
                
        } catch (\Exception $e) {
            \Log::error('Role DataTable Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    /**
     * Store a newly created role
     */
    public function store(RoleRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $role = Role::create([
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ]);

            // Assign permissions
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            // Clear cache
            Cache::forget('roles_list');
            Cache::forget('permissions_list');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully!',
                'role' => $role->load('permissions')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Role Creation Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create role. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update the specified role
     */
    public function update(RoleRequest $request, Role $role)
    {
        DB::beginTransaction();
        
        try {
            $role->update([
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ]);

            // Update permissions
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            // Clear cache
            Cache::forget('roles_list');
            Cache::forget('permissions_list');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully!',
                'role' => $role->load('permissions')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Role Update Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update role. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        try {
            // Check if role is assigned to any users
            if ($role->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete role. It is assigned to one or more users.',
                ], 400);
            }

            $role->delete();

            // Clear cache
            Cache::forget('roles_list');

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Role Deletion Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get role details for editing
     */
    public function show(Role $role)
    {
        return response()->json([
            'role' => $role->load('permissions'),
            'permissions' => Permission::getGroupedByModule()
        ]);
    }

    /**
     * Bulk actions on roles
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:roles,id'
        ]);

        try {
            $action = $request->action;
            $ids = $request->ids;
            
            switch ($action) {
                case 'delete':
                    // Check if any role is assigned to users
                    $assignedRoles = Role::whereIn('id', $ids)
                                        ->whereHas('users')
                                        ->pluck('name')
                                        ->toArray();
                    
                    if (!empty($assignedRoles)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Cannot delete roles: ' . implode(', ', $assignedRoles) . '. They are assigned to users.',
                        ], 400);
                    }
                    
                    Role::whereIn('id', $ids)->delete();
                    $message = 'Roles deleted successfully!';
                    break;
                    
                case 'activate':
                    Role::whereIn('id', $ids)->update(['is_active' => true]);
                    $message = 'Roles activated successfully!';
                    break;
                    
                case 'deactivate':
                    Role::whereIn('id', $ids)->update(['is_active' => false]);
                    $message = 'Roles deactivated successfully!';
                    break;
            }
            
            // Clear cache
            Cache::forget('roles_list');
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'affected_count' => count($ids)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Role Bulk Action Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // Private helper methods
    private function formatStatusBadge($isActive)
    {
        if ($isActive) {
            return '<span class="badge badge-success">Active</span>';
        } else {
            return '<span class="badge badge-danger">Inactive</span>';
        }
    }

    private function formatPermissionsList($permissions)
    {
        if ($permissions->isEmpty()) {
            return '<span class="text-muted">No permissions</span>';
        }

        $badges = $permissions->take(3)->map(function($permission) {
            return '<span class="badge badge-info badge-sm">' . $permission->name . '</span>';
        })->implode(' ');

        if ($permissions->count() > 3) {
            $badges .= ' <span class="badge badge-secondary badge-sm">+' . ($permissions->count() - 3) . ' more</span>';
        }

        return $badges;
    }

    private function formatActionButtons($role)
    {
        $buttons = '';
        
        // View button
        $buttons .= '<button class="btn btn-sm btn-info mr-1" onclick="viewRole(' . $role->id . ')" title="View">
                        <i class="fas fa-eye"></i>
                    </button>';
        
        // Edit button (if user has permission)
        if (auth()->user()->can('roles.edit')) {
            $buttons .= '<button class="btn btn-sm btn-primary mr-1" onclick="editRole(' . $role->id . ')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>';
        }
        
        // Delete button (if user has permission and role is not assigned to users)
        if (auth()->user()->can('roles.delete') && $role->users()->count() === 0) {
            $buttons .= '<button class="btn btn-sm btn-danger" onclick="deleteRole(' . $role->id . ')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>';
        }
        
        return '<div class="btn-group" role="group">' . $buttons . '</div>';
    }
}