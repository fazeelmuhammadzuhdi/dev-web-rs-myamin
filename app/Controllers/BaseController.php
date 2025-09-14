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
}
