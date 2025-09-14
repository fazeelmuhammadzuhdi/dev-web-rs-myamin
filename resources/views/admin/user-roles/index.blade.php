@extends('layouts.app')

@section('title', 'User Role Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-users-cog mr-2"></i>User Role Management
                        </h3>
                        <div class="card-tools">
                            <button class="btn btn-success" onclick="showAssignRoleModal()" id="assignBtn">
                                <i class="fas fa-user-plus"></i> Assign Role
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table id="userRolesTable" class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Permissions</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
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

<!-- Assign Role Modal -->
<div class="modal fade" id="assignRoleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Role to User</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="assignRoleForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select User <span class="text-danger">*</span></label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="">Choose User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="form-group">
                        <label>Select Role <span class="text-danger">*</span></label>
                        <select class="form-control" id="role_id" name="role_id" required>
                            <option value="">Choose Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="form-group">
                        <label>Expires At (Optional)</label>
                        <input type="datetime-local" class="form-control" id="expires_at" name="expires_at">
                        <small class="form-text text-muted">Leave empty for permanent assignment</small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Assign Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Manage User Roles Modal -->
<div class="modal fade" id="manageUserRolesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage User Roles</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="manageUserRolesForm">
                <div class="modal-body">
                    <input type="hidden" id="manage_user_id" name="user_id">
                    
                    <div id="userInfo" class="mb-3">
                        <!-- User info will be loaded here -->
                    </div>
                    
                    <div class="form-group">
                        <label>Assign Roles</label>
                        <div class="roles-container" style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6; padding: 15px; border-radius: 5px;">
                            <!-- Roles will be loaded here -->
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Roles
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Permissions Modal -->
<div class="modal fade" id="viewPermissionsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Permissions</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="permissionsContent">
                    <!-- Permissions will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
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
    .roles-container { background-color: #f8f9fa; }
    .permission-module { border-left: 3px solid #007bff; padding-left: 10px; margin-bottom: 15px; }
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
    $('#userRolesTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('admin.user-roles.data') }}",
            type: 'GET'
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'roles_list', name: 'roles_list', orderable: false, searchable: false},
            {data: 'permissions_count', name: 'permissions_count', orderable: false, searchable: false},
            {data: 'status', name: 'is_active'},
            {data: 'last_login', name: 'last_login_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...',
            emptyTable: 'No users found',
            zeroRecords: 'No matching users found'
        }
    });
}

// Show assign role modal
function showAssignRoleModal() {
    $('#assignRoleForm')[0].reset();
    $('#assignRoleModal').modal('show');
}

// Assign role form submission
$('#assignRoleForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: "{{ route('admin.user-roles.assign') }}",
        type: 'POST',
        data: $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
        success: function(response) {
            if (response.success) {
                $('#assignRoleModal').modal('hide');
                $('#userRolesTable').DataTable().ajax.reload();
                
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
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to assign role'
                });
            }
        }
    });
});

// View user roles
function viewUserRoles(userId) {
    $.ajax({
        url: "{{ route('admin.user-roles.show', '') }}/" + userId,
        type: 'GET',
        success: function(response) {
            const user = response.user;
            
            let userInfo = `
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">${user.name}</h5>
                        <p class="card-text">${user.email}</p>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Current Roles:</strong><br>
                                ${user.roles.map(role => `<span class="badge badge-primary mr-1">${role.name}</span>`).join('')}
                            </div>
                            <div class="col-md-6">
                                <strong>Total Permissions:</strong> ${user.permissions.length}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#userInfo').html(userInfo);
            $('#manage_user_id').val(userId);
            
            // Load roles
            let rolesHtml = '';
            response.roles.forEach(role => {
                const isAssigned = user.roles.some(userRole => userRole.id === role.id);
                rolesHtml += `
                    <div class="form-check">
                        <input class="form-check-input role-checkbox" 
                               type="checkbox" 
                               value="${role.id}" 
                               id="role_${role.id}"
                               name="roles[]"
                               ${isAssigned ? 'checked' : ''}>
                        <label class="form-check-label" for="role_${role.id}">
                            <strong>${role.name}</strong>
                            <br><small class="text-muted">${role.description}</small>
                        </label>
                    </div>
                `;
            });
            
            $('.roles-container').html(rolesHtml);
            $('#manageUserRolesModal').modal('show');
        }
    });
}

// Manage user roles
function manageUserRoles(userId) {
    viewUserRoles(userId);
}

// Manage user roles form submission
$('#manageUserRolesForm').on('submit', function(e) {
    e.preventDefault();
    
    const userId = $('#manage_user_id').val();
    const selectedRoles = $('.role-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    $.ajax({
        url: "{{ route('admin.user-roles.update', '') }}/" + userId,
        type: 'PUT',
        data: {
            roles: selectedRoles,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#manageUserRolesModal').modal('hide');
                $('#userRolesTable').DataTable().ajax.reload();
                
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
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to update user roles'
            });
        }
    });
});

// View user permissions
function viewUserPermissions(userId) {
    $.ajax({
        url: "{{ route('admin.user-roles.permissions', '') }}/" + userId,
        type: 'GET',
        success: function(response) {
            let permissionsHtml = '';
            
            Object.keys(response.permissions).forEach(module => {
                permissionsHtml += `
                    <div class="permission-module">
                        <h6 class="text-primary">
                            <i class="fas fa-folder mr-1"></i>${module.charAt(0).toUpperCase() + module.slice(1)}
                        </h6>
                        <div class="row">
                `;
                
                response.permissions[module].forEach(permission => {
                    permissionsHtml += `
                        <div class="col-md-6 mb-2">
                            <span class="badge badge-info">${permission.name}</span>
                        </div>
                    `;
                });
                
                permissionsHtml += `
                        </div>
                    </div>
                `;
            });
            
            $('#permissionsContent').html(permissionsHtml);
            $('#viewPermissionsModal').modal('show');
        }
    });
}

// Revoke role
function revokeRole(userId, roleId) {
    Swal.fire({
        title: 'Revoke Role',
        text: 'Are you sure you want to revoke this role from the user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, revoke it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('admin.user-roles.revoke') }}",
                type: 'POST',
                data: {
                    user_id: userId,
                    role_id: roleId,
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
                        
                        $('#userRolesTable').DataTable().ajax.reload();
                    }
                }
            });
        }
    });
}
</script>
@endpush