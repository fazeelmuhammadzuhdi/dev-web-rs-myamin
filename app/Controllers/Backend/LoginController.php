<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\User;

/**
 * LoginController handles authentication functionality
 * 
 * This controller manages user login, logout, and security measures
 * including login attempts tracking and password validation.
 */
class LoginController extends BaseController
{
    // Constants for better maintainability
    private const MAX_LOGIN_ATTEMPTS = 3;
    private const LOCKOUT_DURATION = 60; // seconds
    private const MIN_PASSWORD_LENGTH = 8;
    private const STATUS_ACTIVE = 'A';
    
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
     * Display login page
     */
    public function index()
    {
        if (session()->get('idUser')) {
            return redirect()->to(base_url('/home'));
        }

        return view('backend/main/login');
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
     * Get login validation rules
     */
    private function getLoginValidationRules(): array
    {
        return [
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Username tidak boleh kosong',
                    'min_length' => 'Username minimal 3 karakter'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[' . self::MIN_PASSWORD_LENGTH . ']',
                'errors' => [
                    'required' => 'Password tidak boleh kosong',
                    'min_length' => 'Password minimal ' . self::MIN_PASSWORD_LENGTH . ' karakter'
                ]
            ]
        ];
    }

    /**
     * Check if user is locked out due to too many failed attempts
     */
    private function isUserLockedOut(array $user): bool
    {
        if ($user['login_attempts'] >= self::MAX_LOGIN_ATTEMPTS) {
            $timeDiff = time() - strtotime($user['last_attempt']);
            return $timeDiff < self::LOCKOUT_DURATION;
        }
        return false;
    }

    /**
     * Get remaining lockout time
     */
    private function getRemainingLockoutTime(array $user): int
    {
        $timeDiff = time() - strtotime($user['last_attempt']);
        return self::LOCKOUT_DURATION - $timeDiff;
    }

    /**
     * Reset login attempts
     */
    private function resetLoginAttempts(int $userId): void
    {
        $this->userModel->update($userId, [
            'login_attempts' => 0,
            'last_attempt' => null
        ]);
    }

    /**
     * Increment login attempts
     */
    private function incrementLoginAttempts(int $userId): void
    {
        $this->userModel->update($userId, [
            'login_attempts' => $this->userModel->find($userId)['login_attempts'] + 1,
            'last_attempt' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Set user session data
     */
    private function setUserSession(array $user): void
    {
        session()->set([
            'idUser' => $user['iduser'],
            'namaUser' => $user['nama'],
            'usernameUser' => $user['username'],
            'roleUser' => $user['role'],
            'fotoUser' => $user['foto'],
            'statusUser' => $user['status']
        ]);
    }

    /**
     * Update user last login
     */
    private function updateLastLogin(int $userId): void
    {
        $this->userModel->update($userId, [
            'userlastlogin' => date('Y-m-d H:i:s'),
            'login_attempts' => 0
        ]);
    }

    /**
     * Process user login
     */
    public function login()
    {
        $data = $this->getFormData(['username', 'password']);
        $username = $data['username'];
        $password = $data['password'];

        $user = $this->userModel->cekLogin($username);

        // Validate form data
        $rules = $this->getLoginValidationRules();
        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['username', 'password']);
        }

        // Validate password strength
        if (!$this->isValidPassword($password)) {
            session()->setFlashdata('error_password', 'Password tidak valid. Pastikan panjang minimal ' . self::MIN_PASSWORD_LENGTH . ' karakter, terdiri dari huruf besar, huruf kecil, simbol, dan angka.');
            return redirect()->back()->withInput();
        }

        if (!$user) {
            session()->setFlashdata('error', 'Username / Password Salah');
            return redirect()->to('/auth');
        }

        // Check if user is locked out
        if ($this->isUserLockedOut($user)) {
            $remainingTime = $this->getRemainingLockoutTime($user);
            session()->setFlashdata('error', 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $remainingTime . ' detik.');
            session()->setFlashdata('remainingTime', $remainingTime);
            return redirect()->back()->withInput();
        }

        // Reset lockout if time has passed
        if ($user['login_attempts'] >= self::MAX_LOGIN_ATTEMPTS) {
            $this->resetLoginAttempts($user['iduser']);
        }

        // Check user status
        if ($user['status'] !== self::STATUS_ACTIVE) {
            session()->setFlashdata('error', 'Akun belum aktif');
            return redirect()->back()->withInput();
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            $this->setUserSession($user);
            $this->updateLastLogin($user['iduser']);
            return redirect()->to('/home');
        } else {
            $this->incrementLoginAttempts($user['iduser']);
            session()->setFlashdata('error', 'Password salah');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Process user logout
     */
    public function logout()
    {
        $sessionKeys = ['idUser', 'namaUser', 'usernameUser', 'roleUser', 'fotoUser', 'statusUser'];
        
        foreach ($sessionKeys as $key) {
            session()->remove($key);
        }
        
        session()->setFlashdata('success', 'Berhasil keluar');
        return redirect()->to('/auth');
    }
}
