<?php

namespace App\Controllers\Backend;

use App\Models\Slider;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * SliderController handles slider management functionality
 * 
 * This controller manages slider creation, editing, deletion, and display
 * with proper validation, image handling, and content management.
 */
class SliderController extends BaseController
{
    // Constants for better maintainability
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg,image/webp';
    
    // Model instance
    private Slider $sliderModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->sliderModel = new Slider();
    }

    public function index()
    {
        $data['title'] = 'Slider';
        return view('backend/slider/index', $data);
    }

    public function create()
    {
        return view('backend/slider/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->slider->select('idslider,judul,keterangan,gambar');


            return DataTable::of($builder)
                ->edit('gambar', function ($row) {
                    if ($row->gambar !== null) {
                        $imageUrl = base_url('slider/' . $row->gambar);
                        return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" width="160" height="100"></a>';
                    } else {
                        return '';
                    }
                })
                ->edit('keterangan', function ($row) {
                    if ($row->keterangan) {
                        $doc = new DOMDocument();
                        @$doc->loadHTML($row->keterangan);
                        return $doc->textContent; // Menghapus tag HTML
                    }
                    return '-';
                })

                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" judul="Hapus Data" onclick="hapus(\'' . $row->idslider . '\',\'' . $row->judul . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" judul="Edit Data" onclick="edit(\'' . $row->idslider . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    /**
     * Get slider validation rules
     */
    private function getSliderValidationRules(bool $isCreate = true): array
    {
        $rules = [
            'judul' => [
                'label' => 'Judul Slider',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Judul slider harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 100 karakter'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Slider',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan slider harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ]
        ];

        if ($isCreate) {
            $rules['gambar'] = [
                'label' => 'Gambar Slider',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar slider harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG, JPG atau WebP'
                ]
            ];
        } else {
            $rules['gambar'] = [
                'label' => 'Gambar Slider',
                'rules' => 'max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG, JPG atau WebP'
                ]
            ];
        }

        return $rules;
    }

    /**
     * Process image upload
     */
    private function processImageUpload(): array
    {
        $fileFoto = $this->request->getFile('gambar');
        $namaFoto = "Slider_" . $fileFoto->getRandomName();
        
        // Move file to destination
        $fileFoto->move(FCPATH . 'slider', $namaFoto);
        
        // Optimize image
        $optimizedImage = optimizeImageForWeb('slider/' . $namaFoto, [
            'width' => 1920,
            'height' => 800,
            'quality' => 85,
            'thumbnail' => true,
            'thumbnail_width' => 300,
            'thumbnail_height' => 200
        ]);
        
        return [
            'gambar' => $namaFoto,
            'thumbnail' => $namaFoto,
            'optimized' => $optimizedImage['optimized']
        ];
    }

    /**
     * Save new slider
     */
    public function save()
    {
        $data = $this->getFormData(['judul', 'keterangan']);

        $rules = $this->getSliderValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['judul', 'keterangan', 'gambar']);
        }

        $imageData = $this->processImageUpload();

        $this->sliderModel->insert([
            'judul' => $data['judul'],
            'keterangan' => $data['keterangan'],
            'gambar' => $imageData['gambar'],
            'thumbnail' => $imageData['thumbnail'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Slider Berhasil Ditambahkan', '/sliders');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['sliders' => $this->sliderModel->find($id)];
        return view('backend/slider/edit', $data);
    }

    /**
     * Delete old images
     */
    private function deleteOldImages(string $imagePath): void
    {
        if ($imagePath && file_exists(FCPATH . 'slider/' . $imagePath)) {
            $this->deleteFile('slider/' . $imagePath);
        }
    }

    /**
     * Update existing slider
     */
    public function update()
    {
        $data = $this->getFormData(['idslider', 'judul', 'keterangan']);
        $idSlider = $data['idslider'];
        $gambar = $this->request->getFile('gambar');

        $rules = $this->getSliderValidationRules(false);

        // Add image validation if new image is uploaded
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $rules['gambar'] = [
                'label' => 'Gambar Slider',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar slider harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG, JPG atau WebP'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['judul', 'keterangan', 'gambar']);
        }

        $updateData = [
            'judul' => $data['judul'],
            'keterangan' => $data['keterangan'],
        ];

        // Handle image update
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $existingSlider = $this->sliderModel->find($idSlider);
            
            // Delete old image
            if ($existingSlider && $existingSlider['gambar']) {
                $this->deleteOldImages($existingSlider['gambar']);
            }

            // Process new image
            $imageData = $this->processImageUpload();
            $updateData['gambar'] = $imageData['gambar'];
            $updateData['thumbnail'] = $imageData['thumbnail'];
        }

        $this->sliderModel->update($idSlider, $updateData);

        return $this->setSuccessMessage('Data Slider Berhasil Di Update', '/sliders');
    }

    /**
     * Delete slider
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $slider = $this->sliderModel->find($id);

        if (!$slider) {
            return $this->jsonError('Slider tidak ditemukan', 404);
        }

        // Delete associated image
        if ($slider['gambar']) {
            $this->deleteOldImages($slider['gambar']);
        }

        // Delete from database
        $this->sliderModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}
