<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:user-roles.view')->only(['index', 'show']);
        $this->middleware('permission:user-roles.assign')->only(['assign', 'store']);
        $this->middleware('permission:user-roles.revoke')->only(['revoke']);
    }

    /**
     * Display a listing of user roles
     */
    public function index()
    {
        $roles = Role::active()->get();
        $users = User::active()->get();
        
        return view('admin.user-roles.index', compact('roles', 'users'));
    }

    /**
     * Get data for DataTables
     */
    public function getData(Request $request)
    {
        try {
            $query = User::with(['roles' => function($q) {
                $q->where('is_active', true)
                  ->where(function($query) {
                      $query->whereNull('user_roles.expires_at')
                            ->orWhere('user_roles.expires_at', '>', now());
                  });
            }]);

            return DataTables::of($query)
                ->addColumn('roles_list', function ($user) {
                    return $this->formatRolesList($user->roles);
                })
                ->addColumn('permissions_count', function ($user) {
                    return $user->getActivePermissions()->count();
                })
                ->addColumn('status', function ($user) {
                    return $this->formatUserStatusBadge($user->is_active);
                })
                ->addColumn('last_login', function ($user) {
                    return $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Never';
                })
                ->addColumn('actions', function ($user) {
                    return $this->formatActionButtons($user);
                })
                ->rawColumns(['roles_list', 'status', 'actions'])
                ->make(true);
                
        } catch (\Exception $e) {
            \Log::error('User Role DataTable Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    /**
     * Assign role to user
     */
    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'expires_at' => 'nullable|date|after:now'
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $role = Role::findOrFail($request->role_id);

            // Check if user already has this role
            if ($user->hasRole($role->slug)) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already has this role assigned.',
                ], 400);
            }

            // Assign role
            $expiresAt = $request->expires_at ? new \DateTime($request->expires_at) : null;
            $user->assignRole($role, auth()->user(), $expiresAt);

            // Clear cache
            Cache::forget('user_permissions_' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Role assigned successfully!',
                'user' => $user->load('roles')
            ]);

        } catch (\Exception $e) {
            \Log::error('Role Assignment Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign role. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Revoke role from user
     */
    public function revoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $role = Role::findOrFail($request->role_id);

            // Check if user has this role
            if (!$user->hasRole($role->slug)) {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not have this role assigned.',
                ], 400);
            }

            // Revoke role
            $user->removeRole($role);

            // Clear cache
            Cache::forget('user_permissions_' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Role revoked successfully!',
                'user' => $user->load('roles')
            ]);

        } catch (\Exception $e) {
            \Log::error('Role Revocation Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to revoke role. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get user details with roles and permissions
     */
    public function show(User $user)
    {
        $user->load(['roles.permissions', 'permissions']);
        
        return response()->json([
            'user' => $user,
            'roles' => Role::active()->get(),
            'permissions' => Permission::getGroupedByModule()
        ]);
    }

    /**
     * Update user roles
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        try {
            // Sync roles
            $user->syncRoles($request->roles);

            // Clear cache
            Cache::forget('user_permissions_' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'User roles updated successfully!',
                'user' => $user->load('roles')
            ]);

        } catch (\Exception $e) {
            \Log::error('User Roles Update Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update user roles. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get user permissions
     */
    public function getPermissions(User $user)
    {
        $permissions = $user->getActivePermissions();
        
        return response()->json([
            'permissions' => $permissions->groupBy('module')
        ]);
    }

    /**
     * Bulk assign roles
     */
    public function bulkAssign(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'expires_at' => 'nullable|date|after:now'
        ]);

        try {
            $role = Role::findOrFail($request->role_id);
            $expiresAt = $request->expires_at ? new \DateTime($request->expires_at) : null;
            $assignedCount = 0;
            $skippedCount = 0;

            foreach ($request->user_ids as $userId) {
                $user = User::findOrFail($userId);
                
                if (!$user->hasRole($role->slug)) {
                    $user->assignRole($role, auth()->user(), $expiresAt);
                    $assignedCount++;
                } else {
                    $skippedCount++;
                }
            }

            // Clear cache for all affected users
            foreach ($request->user_ids as $userId) {
                Cache::forget('user_permissions_' . $userId);
            }

            return response()->json([
                'success' => true,
                'message' => "Role assigned to {$assignedCount} users. {$skippedCount} users already had this role.",
                'assigned_count' => $assignedCount,
                'skipped_count' => $skippedCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Bulk Role Assignment Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign roles. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // Private helper methods
    private function formatRolesList($roles)
    {
        if ($roles->isEmpty()) {
            return '<span class="text-muted">No roles assigned</span>';
        }

        $badges = $roles->map(function($role) {
            $color = $role->slug === 'admin' ? 'danger' : 
                    ($role->slug === 'moderator' ? 'warning' : 'primary');
            return '<span class="badge badge-' . $color . '">' . $role->name . '</span>';
        })->implode(' ');

        return $badges;
    }

    private function formatUserStatusBadge($isActive)
    {
        if ($isActive) {
            return '<span class="badge badge-success">Active</span>';
        } else {
            return '<span class="badge badge-danger">Inactive</span>';
        }
    }

    private function formatActionButtons($user)
    {
        $buttons = '';
        
        // View button
        $buttons .= '<button class="btn btn-sm btn-info mr-1" onclick="viewUserRoles(' . $user->id . ')" title="View Roles">
                        <i class="fas fa-eye"></i>
                    </button>';
        
        // Manage roles button
        if (auth()->user()->can('user-roles.assign')) {
            $buttons .= '<button class="btn btn-sm btn-primary mr-1" onclick="manageUserRoles(' . $user->id . ')" title="Manage Roles">
                            <i class="fas fa-user-cog"></i>
                        </button>';
        }
        
        // View permissions button
        $buttons .= '<button class="btn btn-sm btn-secondary" onclick="viewUserPermissions(' . $user->id . ')" title="View Permissions">
                        <i class="fas fa-key"></i>
                    </button>';
        
        return '<div class="btn-group" role="group">' . $buttons . '</div>';
    }
}