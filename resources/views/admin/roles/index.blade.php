@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-user-shield mr-2"></i>Role Management
                        </h3>
                        <div class="card-tools">
                            <button class="btn btn-success" onclick="createRole()" id="createBtn">
                                <i class="fas fa-plus"></i> Add Role
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Bulk Actions -->
                    <div class="row mb-3" id="bulkActions" style="display: none;">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span id="selectedCount">0 items selected</span>
                                    <div>
                                        <button class="btn btn-sm btn-danger mr-2" onclick="bulkAction('delete')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                        <button class="btn btn-sm btn-success mr-2" onclick="bulkAction('activate')">
                                            <i class="fas fa-check"></i> Activate
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')">
                                            <i class="fas fa-times"></i> Deactivate
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table id="rolesTable" class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Permissions</th>
                                    <th>Users</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Role</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="roleForm">
                <div class="modal-body">
                    <input type="hidden" id="roleId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Role Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Permissions</label>
                        <div class="permissions-container" style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; padding: 15px; border-radius: 5px;">
                            @foreach($permissions as $module => $modulePermissions)
                                <div class="permission-module mb-3">
                                    <h6 class="text-primary">
                                        <i class="fas fa-folder mr-1"></i>{{ ucfirst($module) }}
                                    </h6>
                                    <div class="row">
                                        @foreach($modulePermissions as $permission)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox" 
                                                           type="checkbox" 
                                                           value="{{ $permission->id }}" 
                                                           id="permission_{{ $permission->id }}"
                                                           name="permissions[]">
                                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-primary" onclick="selectAllPermissions()">
                                <i class="fas fa-check-square"></i> Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="deselectAllPermissions()">
                                <i class="fas fa-square"></i> Deselect All
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <i class="fas fa-save"></i> Save Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<style>
    .table th { background-color: #343a40; color: white; }
    .btn-group .btn { margin-right: 2px; }
    .card { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    .badge { font-size: 0.75em; }
    .permission-module { border-left: 3px solid #007bff; padding-left: 10px; }
    .permissions-container { background-color: #f8f9fa; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    initializeDataTable();
});

// DataTable initialization
function initializeDataTable() {
    $('#rolesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('admin.roles.data') }}",
            type: 'GET'
        },
        columns: [
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<input type="checkbox" class="row-checkbox" value="' + data + '">';
                }
            },
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'permissions', name: 'permissions', orderable: false, searchable: false},
            {data: 'users_count', name: 'users_count', orderable: false, searchable: false},
            {data: 'status', name: 'is_active'},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[1, 'desc']],
        pageLength: 25,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...',
            emptyTable: 'No roles found',
            zeroRecords: 'No matching roles found'
        }
    });

    // Handle row selection
    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });

    $(document).on('change', '.row-checkbox', function() {
        updateBulkActions();
    });
}

// Create role
function createRole() {
    $('#roleForm')[0].reset();
    $('#roleId').val('');
    $('#modalTitle').text('Add Role');
    $('#saveBtn').html('<i class="fas fa-save"></i> Save Role');
    $('.permission-checkbox').prop('checked', false);
    $('#roleModal').modal('show');
}

// Edit role
function editRole(id) {
    $.ajax({
        url: "{{ route('admin.roles.show', '') }}/" + id,
        type: 'GET',
        success: function(response) {
            const role = response.role;
            
            // Populate form fields
            $('#roleId').val(role.id);
            $('#name').val(role.name);
            $('#description').val(role.description);
            $('#is_active').val(role.is_active ? '1' : '0');
            
            // Clear all checkboxes first
            $('.permission-checkbox').prop('checked', false);
            
            // Check permissions for this role
            role.permissions.forEach(function(permission) {
                $('#permission_' + permission.id).prop('checked', true);
            });
            
            $('#modalTitle').text('Edit Role');
            $('#saveBtn').html('<i class="fas fa-save"></i> Update Role');
            $('#roleModal').modal('show');
        }
    });
}

// View role
function viewRole(id) {
    // Implementation for view role
    window.open("{{ route('admin.roles.show', '') }}/" + id, '_blank');
}

// Delete role
function deleteRole(id) {
    Swal.fire({
        title: 'Delete Role',
        text: 'Are you sure you want to delete this role?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('admin.roles.destroy', '') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        $('#rolesTable').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                }
            });
        }
    });
}

// Form submission
$('#roleForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const roleId = $('#roleId').val();
    const url = roleId ? "{{ route('admin.roles.update', '') }}/" + roleId : "{{ route('admin.roles.store') }}";
    const method = roleId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        type: method,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                $('#roleModal').modal('hide');
                $('#rolesTable').DataTable().ajax.reload();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                
                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                // Show new errors
                Object.keys(errors).forEach(field => {
                    $(`#${field}`).addClass('is-invalid');
                    $(`#${field}`).siblings('.invalid-feedback').text(errors[field][0]);
                });
            }
        }
    });
});

// Bulk actions
function bulkAction(action) {
    const selectedIds = $('.row-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selectedIds.length === 0) {
        Swal.fire('Warning', 'Please select at least one role', 'warning');
        return;
    }

    let confirmText = '';
    let confirmIcon = 'warning';
    
    switch (action) {
        case 'delete':
            confirmText = 'Are you sure you want to delete ' + selectedIds.length + ' roles?';
            confirmIcon = 'error';
            break;
        case 'activate':
            confirmText = 'Are you sure you want to activate ' + selectedIds.length + ' roles?';
            confirmIcon = 'question';
            break;
        case 'deactivate':
            confirmText = 'Are you sure you want to deactivate ' + selectedIds.length + ' roles?';
            confirmIcon = 'question';
            break;
    }

    Swal.fire({
        title: 'Confirm Action',
        text: confirmText,
        icon: confirmIcon,
        showCancelButton: true,
        confirmButtonColor: action === 'delete' ? '#d33' : '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
        if (result.isConfirmed) {
            performBulkAction(action, selectedIds);
        }
    });
}

// Perform bulk action
function performBulkAction(action, ids) {
    $.ajax({
        url: "{{ route('admin.roles.bulk-action') }}",
        type: 'POST',
        data: {
            action: action,
            ids: ids,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                $('#rolesTable').DataTable().ajax.reload();
                updateBulkActions();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message
                });
            }
        }
    });
}

// Update bulk actions visibility
function updateBulkActions() {
    const selectedCount = $('.row-checkbox:checked').length;
    
    if (selectedCount > 0) {
        $('#bulkActions').show();
        $('#selectedCount').text(selectedCount + ' items selected');
    } else {
        $('#bulkActions').hide();
    }
}

// Permission selection helpers
function selectAllPermissions() {
    $('.permission-checkbox').prop('checked', true);
}

function deselectAllPermissions() {
    $('.permission-checkbox').prop('checked', false);
}
</script>
@endpush