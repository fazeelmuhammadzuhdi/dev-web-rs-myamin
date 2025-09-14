<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['form', 'url', 'text'];

    /**
     * Common constants for all controllers
     */
    protected const STATUS_ACTIVE = 'Y';
    protected const STATUS_INACTIVE = 'N';
    protected const STATUS_PUBLISHED = 'PB';
    protected const STATUS_DRAFT = 'DR';
    protected const MAX_FILE_SIZE = 1024; // 1MB in KB
    protected const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = \Config\Services::session();
    }

    /**
     * Get form data from request with validation
     */
    protected function getFormData(array $fields): array
    {
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $this->request->getVar($field);
        }
        return $data;
    }

    /**
     * Handle validation errors and redirect back
     */
    protected function handleValidationErrors(array $errorFields = []): \CodeIgniter\HTTP\RedirectResponse
    {
        $validation = \Config\Services::validation();
        $errors = [];
        
        foreach ($errorFields as $field) {
            $errors["error_{$field}"] = $validation->getError($field);
        }
        
        session()->setFlashData($errors);
        return redirect()->back()->withInput();
    }

    /**
     * Set success flash message and redirect
     */
    protected function setSuccessMessage(string $message, string $redirectTo = null): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->setFlashdata('success', $message);
        
        if ($redirectTo) {
            return redirect()->to($redirectTo);
        }
        
        return redirect()->back();
    }

    /**
     * Set error flash message and redirect
     */
    protected function setErrorMessage(string $message, string $redirectTo = null): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->setFlashdata('error', $message);
        
        if ($redirectTo) {
            return redirect()->to($redirectTo);
        }
        
        return redirect()->back();
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjaxRequest(): bool
    {
        return $this->request->isAJAX();
    }

    /**
     * Return JSON response
     */
    protected function jsonResponse(array $data, int $statusCode = 200): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->response->setStatusCode($statusCode)->setJSON($data);
    }

    /**
     * Return error JSON response
     */
    protected function jsonError(string $message, int $statusCode = 400): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Return success JSON response
     */
    protected function jsonSuccess(string $message, array $data = []): \CodeIgniter\HTTP\ResponseInterface
    {
        $response = ['success' => $message];
        if (!empty($data)) {
            $response['data'] = $data;
        }
        return $this->jsonResponse($response);
    }

    /**
     * Validate file upload
     */
    protected function validateFileUpload(\CodeIgniter\Files\File $file, int $maxSize = null, string $allowedTypes = null): array
    {
        $maxSize = $maxSize ?? self::MAX_FILE_SIZE;
        $allowedTypes = $allowedTypes ?? self::ALLOWED_IMAGE_TYPES;
        
        $errors = [];
        
        if (!$file->isValid()) {
            $errors[] = 'File tidak valid';
        }
        
        if ($file->getSize() > ($maxSize * 1024)) {
            $errors[] = "Ukuran file maksimal {$maxSize}KB";
        }
        
        if (!in_array($file->getMimeType(), explode(',', $allowedTypes))) {
            $errors[] = 'Format file tidak diizinkan';
        }
        
        return $errors;
    }

    /**
     * Delete file safely
     */
    protected function deleteFile(string $filePath): bool
    {
        if (file_exists($filePath) && !is_dir($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Get pagination data
     */
    protected function getPaginationData(int $page = 1, int $perPage = 10): array
    {
        return [
            'page' => $page,
            'per_page' => $perPage,
            'offset' => ($page - 1) * $perPage
        ];
    }

    /**
     * Format date for display
     */
    protected function formatDate(string $date, string $format = 'd-m-Y'): string
    {
        return date($format, strtotime($date));
    }

    /**
     * Check if user is logged in (for backend controllers)
     */
    protected function requireLogin(): void
    {
        if (!session()->get('idUser')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }
    }

    /**
     * Get current user ID
     */
    protected function getCurrentUserId(): int
    {
        return session()->get('idUser') ?? 1;
    }

    /**
     * Format status badge for DataTables
     */
    protected function formatStatusBadge(string $status, array $statusMap = []): string
    {
        $defaultMap = [
            'Y' => ['class' => 'badge-success', 'text' => 'Aktif'],
            'N' => ['class' => 'badge-warning', 'text' => 'Tidak Aktif'],
            'A' => ['class' => 'badge-success', 'text' => 'Aktif'],
            'I' => ['class' => 'badge-warning', 'text' => 'Tidak Aktif'],
            'PB' => ['class' => 'badge-success', 'text' => 'Publish'],
            'DR' => ['class' => 'badge-warning', 'text' => 'Draft'],
            'UP' => ['class' => 'badge-warning', 'text' => 'Unpublish']
        ];

        $map = array_merge($defaultMap, $statusMap);
        $statusInfo = $map[$status] ?? ['class' => 'badge-secondary', 'text' => $status];

        return '<span class="badge ' . $statusInfo['class'] . '">' . $statusInfo['text'] . '</span>';
    }

    /**
     * Format action buttons for DataTables
     */
    protected function formatActionButtons(int $id, string $identifier, array $actions = []): string
    {
        $defaultActions = [
            'edit' => [
                'class' => 'btn btn-round btn-primary',
                'icon' => 'feather icon-edit',
                'title' => 'Edit Data',
                'onclick' => "edit('{$id}')"
            ],
            'delete' => [
                'class' => 'btn btn-round btn-danger mx-1',
                'icon' => 'feather icon-trash-2',
                'title' => 'Hapus Data',
                'onclick' => "hapus('{$id}', '{$identifier}')"
            ]
        ];

        $actions = array_merge($defaultActions, $actions);
        $buttons = '';

        foreach ($actions as $action => $config) {
            $buttons .= '<button type="button" class="' . $config['class'] . '" title="' . $config['title'] . '" onclick="' . $config['onclick'] . '">';
            $buttons .= '<i class="' . $config['icon'] . '"></i>';
            $buttons .= '</button>';
        }

        return '<div class="d-flex" role="group">' . $buttons . '</div>';
    }

    /**
     * Format image column for DataTables
     */
    protected function formatImageColumn(?string $imagePath, int $width = 50, int $height = 50): string
    {
        if (empty($imagePath)) {
            return '<span class="text-muted">No Image</span>';
        }

        $fullPath = base_url($imagePath);
        return '<img src="' . $fullPath . '" alt="Image" class="img-thumbnail" style="width: ' . $width . 'px; height: ' . $height . 'px; object-fit: cover;">';
    }

    /**
     * Format user badge for DataTables
     */
    protected function formatUserBadge(?string $username): string
    {
        if (empty($username)) {
            return '<span class="badge badge-secondary">Unknown</span>';
        }

        return '<span class="badge badge-info">' . htmlspecialchars($username) . '</span>';
    }

    /**
     * Format category badge for DataTables
     */
    protected function formatCategoryBadge(string $title): string
    {
        return '<span class="badge badge-primary">' . htmlspecialchars($title) . '</span>';
    }

    /**
     * Generate unique filename for uploads
     */
    protected function generateUniqueFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        $timestamp = time();
        $random = mt_rand(1000, 9999);
        
        return $basename . '_' . $timestamp . '_' . $random . '.' . $extension;
    }

    /**
     * Move uploaded file to destination
     */
    protected function moveUploadedFile(\CodeIgniter\Files\File $file, string $destination): bool
    {
        if (!$file->isValid()) {
            return false;
        }

        $newName = $this->generateUniqueFilename($file->getName());
        $fullPath = FCPATH . $destination . '/' . $newName;

        // Create directory if not exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return $file->move($dir, $newName);
    }

    /**
     * Get validation rules for common fields
     */
    protected function getCommonValidationRules(): array
    {
        return [
            'required' => 'required',
            'min_length_3' => 'min_length[3]',
            'max_length_50' => 'max_length[50]',
            'max_length_100' => 'max_length[100]',
            'max_length_200' => 'max_length[200]',
            'valid_email' => 'valid_email',
            'numeric' => 'numeric',
            'integer' => 'integer',
            'alpha_numeric' => 'alpha_numeric',
            'alpha_numeric_space' => 'alpha_numeric_space'
        ];
    }

    /**
     * Get common error messages
     */
    protected function getCommonErrorMessages(): array
    {
        return [
            'required' => '{field} tidak boleh kosong',
            'min_length' => '{field} minimal {param} karakter',
            'max_length' => '{field} maksimal {param} karakter',
            'valid_email' => '{field} harus berupa email yang valid',
            'numeric' => '{field} harus berupa angka',
            'integer' => '{field} harus berupa bilangan bulat',
            'alpha_numeric' => '{field} hanya boleh berisi huruf dan angka',
            'alpha_numeric_space' => '{field} hanya boleh berisi huruf, angka, dan spasi'
        ];
    }
}
