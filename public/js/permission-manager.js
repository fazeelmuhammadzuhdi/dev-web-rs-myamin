/**
 * Permission Manager - Handle multiple permissions dynamically
 */
class PermissionManager {
    constructor() {
        this.permissions = {};
        this.accessibleActions = [];
        this.permissionLevel = 'none';
        this.init();
    }

    init() {
        this.loadPermissions();
        this.bindEvents();
    }

    /**
     * Load user permissions from server
     */
    async loadPermissions() {
        try {
            const response = await fetch('/api/user/permissions');
            const data = await response.json();
            
            this.permissions = data.permissions;
            this.accessibleActions = data.accessible_actions;
            this.permissionLevel = data.permission_level;
            
            this.updateUI();
        } catch (error) {
            console.error('Failed to load permissions:', error);
        }
    }

    /**
     * Check if user has specific permission
     */
    hasPermission(permission) {
        return this.permissions[permission] || false;
    }

    /**
     * Check if user has any of the specified permissions
     */
    hasAnyPermission(permissions) {
        return permissions.some(permission => this.hasPermission(permission));
    }

    /**
     * Check if user has all of the specified permissions
     */
    hasAllPermissions(permissions) {
        return permissions.every(permission => this.hasPermission(permission));
    }

    /**
     * Check if user can perform specific action
     */
    canPerformAction(action) {
        return this.accessibleActions.includes(action);
    }

    /**
     * Check if user has specific permission level
     */
    hasPermissionLevel(level) {
        const levels = ['none', 'view', 'create', 'edit', 'delete', 'admin'];
        const currentLevelIndex = levels.indexOf(this.permissionLevel);
        const requiredLevelIndex = levels.indexOf(level);
        
        return currentLevelIndex >= requiredLevelIndex;
    }

    /**
     * Show/hide elements based on permissions
     */
    updateUI() {
        // Update buttons
        this.updateButtons();
        
        // Update menu items
        this.updateMenu();
        
        // Update form fields
        this.updateFormFields();
        
        // Update table columns
        this.updateTableColumns();
    }

    /**
     * Update buttons based on permissions
     */
    updateButtons() {
        // Create button
        if (!this.canPerformAction('create')) {
            $('.create-btn, .add-btn').hide();
        }

        // Edit button
        if (!this.canPerformAction('edit')) {
            $('.edit-btn, .update-btn').hide();
        }

        // Delete button
        if (!this.canPerformAction('delete')) {
            $('.delete-btn, .remove-btn').hide();
        }

        // Export button
        if (!this.canPerformAction('export')) {
            $('.export-btn, .download-btn').hide();
        }

        // Import button
        if (!this.canPerformAction('import')) {
            $('.import-btn, .upload-btn').hide();
        }

        // Admin features
        if (!this.hasPermissionLevel('admin')) {
            $('.admin-btn, .advanced-btn').hide();
        }
    }

    /**
     * Update menu items based on permissions
     */
    updateMenu() {
        // Hide menu items user can't access
        $('.menu-item').each(function() {
            const permission = $(this).data('permission');
            if (permission && !window.permissionManager.hasPermission(permission)) {
                $(this).hide();
            }
        });

        // Hide submenu items
        $('.submenu-item').each(function() {
            const permission = $(this).data('permission');
            if (permission && !window.permissionManager.hasPermission(permission)) {
                $(this).hide();
            }
        });
    }

    /**
     * Update form fields based on permissions
     */
    updateFormFields() {
        // Hide advanced fields for non-admin users
        if (!this.hasPermissionLevel('admin')) {
            $('.advanced-field, .admin-field').hide();
        }

        // Hide premium features for non-premium users
        if (!this.hasPermission('premium.features')) {
            $('.premium-field').hide();
        }
    }

    /**
     * Update table columns based on permissions
     */
    updateTableColumns() {
        // Hide action column if user has no action permissions
        if (!this.hasAnyPermission(['edit', 'delete', 'view'])) {
            $('.actions-column').hide();
        }

        // Hide bulk action column if user has no bulk permissions
        if (!this.hasAnyPermission(['bulk_edit', 'bulk_delete'])) {
            $('.bulk-column').hide();
        }
    }

    /**
     * Generate action buttons based on permissions
     */
    generateActionButtons(row) {
        let buttons = '';

        // View button
        if (this.canPerformAction('view')) {
            buttons += `
                <button class="btn btn-sm btn-info mr-1" onclick="viewItem(${row.id})" title="View">
                    <i class="fas fa-eye"></i>
                </button>
            `;
        }

        // Edit button
        if (this.canPerformAction('edit')) {
            buttons += `
                <button class="btn btn-sm btn-primary mr-1" onclick="editItem(${row.id})" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
            `;
        }

        // Delete button
        if (this.canPerformAction('delete')) {
            buttons += `
                <button class="btn btn-sm btn-danger" onclick="deleteItem(${row.id})" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }

        return `<div class="btn-group" role="group">${buttons}</div>`;
    }

    /**
     * Check permission before performing action
     */
    checkPermission(action, callback) {
        if (this.canPerformAction(action)) {
            callback();
        } else {
            this.showPermissionError(action);
        }
    }

    /**
     * Show permission error message
     */
    showPermissionError(action) {
        Swal.fire({
            icon: 'error',
            title: 'Access Denied',
            text: `You do not have permission to ${action}`,
            confirmButtonText: 'OK'
        });
    }

    /**
     * Bind events
     */
    bindEvents() {
        // Check permissions on form submission
        $('form').on('submit', (e) => {
            const form = $(e.target);
            const requiredPermission = form.data('permission');
            
            if (requiredPermission && !this.hasPermission(requiredPermission)) {
                e.preventDefault();
                this.showPermissionError('submit this form');
            }
        });

        // Check permissions on button clicks
        $('.permission-btn').on('click', (e) => {
            const button = $(e.target).closest('.permission-btn');
            const requiredPermission = button.data('permission');
            
            if (requiredPermission && !this.hasPermission(requiredPermission)) {
                e.preventDefault();
                this.showPermissionError('perform this action');
            }
        });
    }

    /**
     * Get permission matrix for current user
     */
    getPermissionMatrix() {
        return {
            permissions: this.permissions,
            accessible_actions: this.accessibleActions,
            permission_level: this.permissionLevel,
            can_create: this.canPerformAction('create'),
            can_edit: this.canPerformAction('edit'),
            can_delete: this.canPerformAction('delete'),
            can_export: this.canPerformAction('export'),
            can_import: this.canPerformAction('import'),
            is_admin: this.hasPermissionLevel('admin')
        };
    }

    /**
     * Refresh permissions (useful after role changes)
     */
    async refreshPermissions() {
        await this.loadPermissions();
        this.updateUI();
    }
}

// Initialize permission manager
window.permissionManager = new PermissionManager();

// Helper functions for easy access
function hasPermission(permission) {
    return window.permissionManager.hasPermission(permission);
}

function hasAnyPermission(permissions) {
    return window.permissionManager.hasAnyPermission(permissions);
}

function hasAllPermissions(permissions) {
    return window.permissionManager.hasAllPermissions(permissions);
}

function canPerformAction(action) {
    return window.permissionManager.canPerformAction(action);
}

function checkPermission(action, callback) {
    window.permissionManager.checkPermission(action, callback);
}

// Usage examples:
/*
// Check single permission
if (hasPermission('products.create')) {
    showCreateButton();
}

// Check multiple permissions (ANY)
if (hasAnyPermission(['products.create', 'products.edit', 'products.import'])) {
    showProductManagement();
}

// Check multiple permissions (ALL)
if (hasAllPermissions(['products.create', 'products.edit', 'products.delete'])) {
    showFullProductManagement();
}

// Check action permission
if (canPerformAction('create')) {
    enableCreateFeature();
}

// Check permission before action
checkPermission('delete', function() {
    deleteItem();
});
*/