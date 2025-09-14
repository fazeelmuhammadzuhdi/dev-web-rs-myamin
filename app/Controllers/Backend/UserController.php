<?php

namespace App\Controllers\Backend;

use App\Models\User;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * UserController handles user management functionality
 * 
 * This controller manages user creation, editing, deletion, and display
 * with proper validation and security measures.
 */
class UserController extends BaseController
{
    // Constants for better maintainability
    private const ROLE_SUPER_ADMIN = 'SU';
    private const ROLE_USER = 'US';
    private const STATUS_ACTIVE = 'A';
    private const STATUS_INACTIVE = 'I';
    private const MIN_PASSWORD_LENGTH = 8;
    
    // Model instance
    private User $userModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Display user index page
     */
    public function index()
    {
        $data = ['title' => 'User'];
        return view('backend/user/index', $data);
    }

    /**
     * Display user creation form
     */
    public function create()
    {
        return view('backend/user/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->userModel->select('iduser,username,nama,role,status');
        
        return DataTable::of($builder)
            ->edit('role', function ($row) {
                return $this->formatRoleBadge($row->role);
            })
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status, ['A' => ['class' => 'badge-success', 'text' => 'Aktif'], 'I' => ['class' => 'badge-danger', 'text' => 'Tidak Aktif']]);
            })
            ->add('action', function ($row) {
                return $this->formatUserActionButtons($row->iduser, $row->username);
            }, 'last')
            ->toJson();
    }

    /**
     * Format role badge
     */
    private function formatRoleBadge(string $role): string
    {
        if ($role === self::ROLE_SUPER_ADMIN) {
            return '<span class="badge badge-success">Super Admin</span>';
        }
        
        return '<span class="badge badge-danger">User</span>';
    }

    /**
     * Format action buttons - using BaseController protected method
     */
    private function formatUserActionButtons(int $id, string $username): string
    {
        return $this->formatActionButtons($id, $username);
    }


    /**
     * Validate password strength
     */
    private function isValidPassword(string $password): bool
    {
        // Check minimum length
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            return false;
        }

        // Check for uppercase, lowercase, number, and special character
        if (
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W_]/', $password)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Get password validation error message
     */
    private function getPasswordErrorMessage(): string
    {
        return 'Password tidak valid. Pastikan panjang minimal ' . self::MIN_PASSWORD_LENGTH . ' karakter, terdiri dari huruf besar, huruf kecil, simbol, dan angka';
    }

    /**
     * Get user validation rules
     */
    private function getUserValidationRules(bool $isCreate = true): array
    {
        $rules = [
            'nama' => [
                'label' => 'Nama User',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama user harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'min_length' => 'Username minimal 3 karakter',
                    'max_length' => 'Username maksimal 50 karakter'
                ]
            ]
        ];

        if ($isCreate) {
            $rules['password'] = [
                'label' => 'Password',
                'rules' => 'required|min_length[' . self::MIN_PASSWORD_LENGTH . ']',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal ' . self::MIN_PASSWORD_LENGTH . ' karakter'
                ]
            ];
        }

        return $rules;
    }

    /**
     * Save new user
     */
    public function save()
    {
        $data = $this->getFormData(['nama', 'role', 'username', 'password']);
        $password = $data['password'];

        // Validate password strength
        if (!$this->isValidPassword($password)) {
            session()->setFlashdata('error_password', $this->getPasswordErrorMessage());
            return redirect()->back()->withInput();
        }

        $rules = $this->getUserValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'username', 'password']);
        }

        $this->userModel->insert([
            'nama' => $data['nama'],
            'username' => $data['username'],
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $data['role'] ?: self::ROLE_USER,
            'status' => self::STATUS_ACTIVE,
            'usercreate' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data User Berhasil Ditambahkan', '/users');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['user' => $this->userModel->find($id)];
        return view('backend/user/edit', $data);
    }

    /**
     * Update existing user
     */
    public function update()
    {
        $idUser = $this->request->getVar('iduser');
        $data = $this->getFormData(['nama', 'role', 'username', 'status', 'password']);
        $password = $data['password'];
        
        $existingUser = $this->userModel->find($idUser);

        // Validate password if provided
        if (!empty($password)) {
            if (!$this->isValidPassword($password)) {
                session()->setFlashdata('error_password', $this->getPasswordErrorMessage());
                return redirect()->back()->withInput();
            }
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        $rules = $this->getUserValidationRules(false);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'username']);
        }

        $updateData = [
            'nama' => $data['nama'],
            'username' => $data['username'],
            'role' => $data['role'] ?: self::ROLE_USER,
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
        ];

        // Only update password if new password is provided
        if (!empty($password)) {
            $updateData['password'] = $password;
        } else {
            $updateData['password'] = $existingUser['password'];
        }

        $this->userModel->update($idUser, $updateData);

        return $this->setSuccessMessage('Data User Berhasil Di Update', '/users');
    }

    /**
     * Delete user
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->jsonError('User tidak ditemukan', 404);
        }

        $this->userModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}
