@extends('layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-boxes mr-2"></i>Products Management
                        </h3>
                        <div class="card-tools">
                            {{-- Multiple permission checks for buttons --}}
                            @if($permissions['can_create'])
                                <button class="btn btn-success mr-2" onclick="createProduct()">
                                    <i class="fas fa-plus"></i> Add Product
                                </button>
                            @endif
                            
                            @if($permissions['can_import'])
                                <button class="btn btn-info mr-2" onclick="importProducts()">
                                    <i class="fas fa-upload"></i> Import
                                </button>
                            @endif
                            
                            @if($permissions['can_export'])
                                <button class="btn btn-warning" onclick="exportProducts()">
                                    <i class="fas fa-download"></i> Export
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    {{-- Bulk Actions - only show if user has multiple permissions --}}
                    @if($permissions['can_bulk_edit'] || $permissions['can_delete'])
                        <div class="row mb-3" id="bulkActions" style="display: none;">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span id="selectedCount">0 items selected</span>
                                        <div>
                                            @if($permissions['can_delete'])
                                                <button class="btn btn-sm btn-danger mr-2" onclick="bulkAction('delete')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            @endif
                                            
                                            @if($permissions['can_bulk_edit'])
                                                <button class="btn btn-sm btn-primary mr-2" onclick="bulkAction('activate')">
                                                    <i class="fas fa-check"></i> Activate
                                                </button>
                                                <button class="btn btn-sm btn-warning mr-2" onclick="bulkAction('deactivate')">
                                                    <i class="fas fa-times"></i> Deactivate
                                                </button>
                                            @endif
                                            
                                            @if($permissions['can_export'])
                                                <button class="btn btn-sm btn-info" onclick="bulkAction('export')">
                                                    <i class="fas fa-download"></i> Export
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table id="productsTable" class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    @if($permissions['can_bulk_edit'] || $permissions['can_delete'])
                                        <th width="30">
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                    @endif
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    @if($permissions['can_edit'] || $permissions['can_delete'] || $permissions['can_view'])
                                        <th width="150">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="productForm">
                <div class="modal-body">
                    <input type="hidden" id="productId" name="id">
                    
                    <div class="form-group">
                        <label>Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Price <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>
                    
                    {{-- Advanced features - only show if user has multiple permissions --}}
                    @if($permissions['can_full_manage'])
                        <div class="form-group">
                            <label>Advanced Settings</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="premium_features" name="premium_features">
                                <label class="form-check-label" for="premium_features">
                                    Enable Premium Features
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <i class="fas fa-save"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    initializeDataTable();
    loadUserPermissions();
});

// Load user permissions for JavaScript
function loadUserPermissions() {
    $.ajax({
        url: "{{ route('admin.products.permissions') }}",
        type: 'GET',
        success: function(response) {
            window.userPermissions = response.permissions;
            window.accessibleActions = response.accessible_actions;
            window.permissionLevel = response.permission_level;
            
            // Show/hide features based on permissions
            updateUI();
        }
    });
}

// Update UI based on permissions
function updateUI() {
    // Show/hide buttons based on permissions
    if (!window.accessibleActions.includes('create')) {
        $('.create-btn').hide();
    }
    
    if (!window.accessibleActions.includes('export')) {
        $('.export-btn').hide();
    }
    
    if (!window.accessibleActions.includes('import')) {
        $('.import-btn').hide();
    }
    
    // Show advanced features only for admin level
    if (window.permissionLevel !== 'admin') {
        $('.advanced-features').hide();
    }
}

// DataTable with permission-based columns
function initializeDataTable() {
    const columns = [];
    
    // Add checkbox column only if user has bulk permissions
    @if($permissions['can_bulk_edit'] || $permissions['can_delete'])
        columns.push({
            data: 'id',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                return '<input type="checkbox" class="row-checkbox" value="' + data + '">';
            }
        });
    @endif
    
    // Standard columns
    columns.push(
        {data: 'id', name: 'id'},
        {data: 'name', name: 'name'},
        {data: 'price', name: 'price'},
        {data: 'status', name: 'status'},
        {data: 'created_at', name: 'created_at'}
    );
    
    // Add actions column only if user has any action permissions
    @if($permissions['can_edit'] || $permissions['can_delete'] || $permissions['can_view'])
        columns.push({
            data: 'actions',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                return generateActionButtons(row);
            }
        });
    @endif
    
    $('#productsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('admin.products.data') }}",
            type: 'GET'
        },
        columns: columns,
        order: [[1, 'desc']],
        pageLength: 25
    });
}

// Generate action buttons based on permissions
function generateActionButtons(row) {
    let buttons = '';
    
    // View button - always available if user can view
    @if($permissions['can_view'])
        buttons += '<button class="btn btn-sm btn-info mr-1" onclick="viewProduct(' + row.id + ')" title="View">';
        buttons += '<i class="fas fa-eye"></i></button>';
    @endif
    
    // Edit button - only if user can edit
    @if($permissions['can_edit'])
        buttons += '<button class="btn btn-sm btn-primary mr-1" onclick="editProduct(' + row.id + ')" title="Edit">';
        buttons += '<i class="fas fa-edit"></i></button>';
    @endif
    
    // Delete button - only if user can delete
    @if($permissions['can_delete'])
        buttons += '<button class="btn btn-sm btn-danger" onclick="deleteProduct(' + row.id + ')" title="Delete">';
        buttons += '<i class="fas fa-trash"></i></button>';
    @endif
    
    return '<div class="btn-group" role="group">' + buttons + '</div>';
}

// Create product - check permission
function createProduct() {
    if (!window.accessibleActions.includes('create')) {
        Swal.fire('Error', 'You do not have permission to create products', 'error');
        return;
    }
    
    $('#productForm')[0].reset();
    $('#productId').val('');
    $('#modalTitle').text('Add Product');
    $('#saveBtn').html('<i class="fas fa-save"></i> Save Product');
    $('#productModal').modal('show');
}

// Edit product - check permission
function editProduct(id) {
    if (!window.accessibleActions.includes('edit')) {
        Swal.fire('Error', 'You do not have permission to edit products', 'error');
        return;
    }
    
    // Your edit logic here
}

// Delete product - check permission
function deleteProduct(id) {
    if (!window.accessibleActions.includes('delete')) {
        Swal.fire('Error', 'You do not have permission to delete products', 'error');
        return;
    }
    
    // Your delete logic here
}

// Bulk actions - check multiple permissions
function bulkAction(action) {
    const permissionMap = {
        'delete': 'delete',
        'activate': 'edit',
        'deactivate': 'edit',
        'export': 'export'
    };
    
    const requiredAction = permissionMap[action];
    
    if (!window.accessibleActions.includes(requiredAction)) {
        Swal.fire('Error', 'You do not have permission to perform this action', 'error');
        return;
    }
    
    // Your bulk action logic here
}

// Export products - check permission
function exportProducts() {
    if (!window.accessibleActions.includes('export')) {
        Swal.fire('Error', 'You do not have permission to export products', 'error');
        return;
    }
    
    // Your export logic here
}

// Import products - check permission
function importProducts() {
    if (!window.accessibleActions.includes('import')) {
        Swal.fire('Error', 'You do not have permission to import products', 'error');
        return;
    }
    
    // Your import logic here
}
</script>
@endpush