<?php

namespace App\Controllers\Backend;

use App\Models\Tentang;
use App\Controllers\BaseController;

/**
 * TentangController handles about us page management functionality
 * 
 * This controller manages about us page content including
 * about section, history section, and bed availability information.
 */
class TentangController extends BaseController
{
    // Model instance
    private Tentang $tentangModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->tentangModel = new Tentang();
    }

    /**
     * Display about us index page
     */
    public function index()
    {
        $data = [
            'title' => 'Tentang',
            'tentang' => $this->tentangModel->first()
        ];
        return view('backend/tentang/index', $data);
    }

    /**
     * Get about us validation rules
     */
    private function getAboutUsValidationRules(): array
    {
        return [
            'title_tentang' => [
                'label' => 'Judul Tentang',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Judul tentang harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 100 karakter'
                ]
            ],
            'konten_tentang' => [
                'label' => 'Konten Tentang',
                'rules' => 'required|min_length[20]',
                'errors' => [
                    'required' => 'Konten tentang harus diisi',
                    'min_length' => 'Konten minimal 20 karakter'
                ]
            ],
            'title_sejarah' => [
                'label' => 'Judul Sejarah',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Judul sejarah harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 100 karakter'
                ]
            ],
            'konten_sejarah' => [
                'label' => 'Konten Sejarah',
                'rules' => 'required|min_length[20]',
                'errors' => [
                    'required' => 'Konten sejarah harus diisi',
                    'min_length' => 'Konten minimal 20 karakter'
                ]
            ],
            'ketersediaan_tempat_tidur' => [
                'label' => 'Ketersediaan Tempat Tidur',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Ketersediaan tempat tidur harus diisi',
                    'min_length' => 'Ketersediaan minimal 10 karakter'
                ]
            ]
        ];
    }

    /**
     * Save or update about us content
     */
    public function save()
    {
        $data = $this->getFormData([
            'id', 'title_tentang', 'konten_tentang', 'title_sejarah', 
            'konten_sejarah', 'ketersediaan_tempat_tidur'
        ]);

        $rules = $this->getAboutUsValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'title_tentang', 'konten_tentang', 'title_sejarah', 
                'konten_sejarah', 'ketersediaan_tempat_tidur'
            ]);
        }

        $aboutData = [
            'title_tentang' => $data['title_tentang'],
            'konten_tentang' => $data['konten_tentang'],
            'title_sejarah' => $data['title_sejarah'],
            'konten_sejarah' => $data['konten_sejarah'],
            'ketersediaan_tempat_tidur' => $data['ketersediaan_tempat_tidur'],
        ];

        // Save or update about us content
        if ($data['id']) {
            $this->tentangModel->update($data['id'], $aboutData);
        } else {
            $this->tentangModel->insert($aboutData);
        }

        return $this->setSuccessMessage('Data Tentang Berhasil Di Update', '/tentangs');
    }
}