<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\AdvancedPermissionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        // Multiple permission checks
        $this->middleware(function ($request, $next) {
            // Check if user has ANY product-related permission
            if (!AdvancedPermissionHelper::hasAnyPermission([
                'products.view',
                'products.create', 
                'products.edit',
                'products.delete'
            ])) {
                abort(403, 'You do not have permission to access products');
            }
            
            return $next($request);
        });
    }

    /**
     * Display products - requires view permission
     */
    public function index()
    {
        // Check multiple permissions for different features
        $permissions = [
            'can_view' => Auth::user()->hasPermission('products.view'),
            'can_create' => Auth::user()->hasPermission('products.create'),
            'can_edit' => Auth::user()->hasPermission('products.edit'),
            'can_delete' => Auth::user()->hasPermission('products.delete'),
            'can_export' => Auth::user()->hasPermission('products.export'),
            'can_import' => Auth::user()->hasPermission('products.import'),
            'can_bulk_edit' => AdvancedPermissionHelper::hasAllPermissions([
                'products.view',
                'products.edit'
            ]),
            'can_full_manage' => AdvancedPermissionHelper::hasAllPermissions([
                'products.view',
                'products.create',
                'products.edit',
                'products.delete'
            ])
        ];

        return view('admin.products.index', compact('permissions'));
    }

    /**
     * Create product - requires create permission
     */
    public function create()
    {
        // Check if user has create permission
        if (!Auth::user()->hasPermission('products.create')) {
            abort(403, 'You do not have permission to create products');
        }

        // Check if user also has view permission for better UX
        $canView = Auth::user()->hasPermission('products.view');
        
        return view('admin.products.create', compact('canView'));
    }

    /**
     * Store product - requires create permission
     */
    public function store(Request $request)
    {
        // Multiple permission validation
        if (!Auth::user()->hasPermission('products.create')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to create products'
            ], 403);
        }

        // Additional check for premium features
        if ($request->has('premium_features')) {
            if (!AdvancedPermissionHelper::hasAllPermissions([
                'products.create',
                'products.premium'
            ])) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to use premium features'
                ], 403);
            }
        }

        // Your store logic here
        return response()->json(['success' => true]);
    }

    /**
     * Edit product - requires edit permission
     */
    public function edit($id)
    {
        // Check edit permission
        if (!Auth::user()->hasPermission('products.edit')) {
            abort(403, 'You do not have permission to edit products');
        }

        // Check if user can also delete (for delete button)
        $canDelete = Auth::user()->hasPermission('products.delete');
        
        return view('admin.products.edit', compact('canDelete'));
    }

    /**
     * Update product - requires edit permission
     */
    public function update(Request $request, $id)
    {
        // Check edit permission
        if (!Auth::user()->hasPermission('products.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to edit products'
            ], 403);
        }

        // Check for advanced editing features
        if ($request->has('advanced_editing')) {
            if (!AdvancedPermissionHelper::hasAllPermissions([
                'products.edit',
                'products.advanced'
            ])) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission for advanced editing'
                ], 403);
            }
        }

        // Your update logic here
        return response()->json(['success' => true]);
    }

    /**
     * Delete product - requires delete permission
     */
    public function destroy($id)
    {
        // Check delete permission
        if (!Auth::user()->hasPermission('products.delete')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete products'
            ], 403);
        }

        // Your delete logic here
        return response()->json(['success' => true]);
    }

    /**
     * Bulk operations - requires multiple permissions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        
        // Different permissions for different bulk actions
        $permissionMap = [
            'delete' => 'products.delete',
            'activate' => 'products.edit',
            'deactivate' => 'products.edit',
            'export' => 'products.export',
            'import' => 'products.import',
            'bulk_edit' => ['products.view', 'products.edit']
        ];

        $requiredPermission = $permissionMap[$action] ?? null;
        
        if (!$requiredPermission) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid action'
            ], 400);
        }

        // Check if it's array of permissions (multiple)
        if (is_array($requiredPermission)) {
            if (!AdvancedPermissionHelper::hasAllPermissions($requiredPermission)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to perform this bulk action'
                ], 403);
            }
        } else {
            // Single permission check
            if (!Auth::user()->hasPermission($requiredPermission)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to perform this bulk action'
                ], 403);
            }
        }

        // Your bulk action logic here
        return response()->json(['success' => true]);
    }

    /**
     * Get user's product permissions for frontend
     */
    public function getPermissions()
    {
        $permissions = AdvancedPermissionHelper::getMenuPermissions()['products'];
        
        return response()->json([
            'permissions' => $permissions,
            'accessible_actions' => AdvancedPermissionHelper::getAccessibleActions('products'),
            'permission_level' => AdvancedPermissionHelper::getPermissionLevel('products')
        ]);
    }

    /**
     * Advanced feature access
     */
    public function advancedFeatures()
    {
        // Check multiple conditions
        $canAccess = AdvancedPermissionHelper::canAccessFeature([
            [
                'type' => 'permission',
                'value' => 'products.view'
            ],
            [
                'type' => 'permission', 
                'value' => 'products.advanced'
            ],
            [
                'type' => 'role',
                'value' => 'admin'
            ],
            [
                'type' => 'custom',
                'callback' => function($user) {
                    // Custom logic - e.g., user must be active for 30 days
                    return $user->created_at->diffInDays(now()) >= 30;
                }
            ]
        ]);

        if (!$canAccess) {
            abort(403, 'You do not have access to advanced features');
        }

        return view('admin.products.advanced');
    }
}