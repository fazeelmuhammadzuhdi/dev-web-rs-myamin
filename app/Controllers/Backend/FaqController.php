<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\FAQ;
use Hermawan\DataTables\DataTable;

/**
 * FaqController handles FAQ management functionality
 * 
 * This controller manages FAQ creation, editing, deletion, and display
 * with proper validation and content management.
 */
class FaqController extends BaseController
{
    // Model instance
    private FAQ $faqModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->faqModel = new FAQ();
    }

    /**
     * Display FAQ index page
     */
    public function index()
    {
        $data = ['title' => 'FAQ'];
        return view('backend/pertanyaan/index', $data);
    }

    /**
     * Display FAQ creation form
     */
    public function create()
    {
        return view('backend/pertanyaan/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->faqModel
            ->select('idfaq,pertanyaan,jawaban,created_at')
            ->orderBy('created_at', 'desc')
            ->orderBy('idfaq', 'DESC');
        
        return DataTable::of($builder)
            ->edit('jawaban', function ($row) {
                return $this->formatAnswerColumn($row->jawaban);
            })
            ->edit('created_at', function ($row) {
                return $this->formatDateColumn($row->created_at);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idfaq, $row->pertanyaan);
            }, 'last')
            ->toJson();
    }

    /**
     * Format answer column
     */
    private function formatAnswerColumn(?string $jawaban): string
    {
        if ($jawaban) {
            // Strip HTML tags and limit length
            $text = strip_tags($jawaban);
            return strlen($text) > 100 ? substr($text, 0, 100) . '...' : $text;
        }
        
        return '-';
    }

    /**
     * Format date column
     */
    private function formatDateColumn(string $createdAt): string
    {
        return '<span class="text-nowrap">' . tanggal_indonesia($createdAt) . '</span>';
    }

    /**
     * Format action buttons
     */
    private function formatActionButtons(int $id, string $pertanyaan): string
    {
        return '<div class="d-flex" role="group">
            <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $id . '\',\'' . esc($pertanyaan) . '\')">
                <i class="feather icon-trash-2"></i>
            </button>
            <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $id . '\')">
                <i class="feather icon-edit"></i>
            </button>
        </div>';
    }

    /**
     * Get FAQ validation rules
     */
    private function getFaqValidationRules(): array
    {
        return [
            'pertanyaan' => [
                'label' => 'Pertanyaan',
                'rules' => 'required|min_length[10]|max_length[255]',
                'errors' => [
                    'required' => 'Pertanyaan harus diisi',
                    'min_length' => 'Pertanyaan minimal 10 karakter',
                    'max_length' => 'Pertanyaan maksimal 255 karakter'
                ]
            ],
            'jawaban' => [
                'label' => 'Jawaban',
                'rules' => 'required|min_length[20]',
                'errors' => [
                    'required' => 'Jawaban harus diisi',
                    'min_length' => 'Jawaban minimal 20 karakter'
                ]
            ]
        ];
    }

    /**
     * Save new FAQ
     */
    public function save()
    {
        $data = $this->getFormData(['pertanyaan', 'jawaban']);

        $rules = $this->getFaqValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['pertanyaan', 'jawaban']);
        }

        $this->faqModel->insert([
            'pertanyaan' => $data['pertanyaan'],
            'jawaban' => $data['jawaban'],
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->setSuccessMessage('Data FAQ Berhasil Ditambahkan', '/faqs');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['faq' => $this->faqModel->find($id)];
        return view('backend/pertanyaan/edit', $data);
    }

    /**
     * Update existing FAQ
     */
    public function update()
    {
        $data = $this->getFormData(['idfaq', 'pertanyaan', 'jawaban']);
        $idfaq = $data['idfaq'];

        $rules = $this->getFaqValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['pertanyaan', 'jawaban']);
        }

        $this->faqModel->update($idfaq, [
            'pertanyaan' => $data['pertanyaan'],
            'jawaban' => $data['jawaban'],
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->setSuccessMessage('Data FAQ Berhasil Di Update', '/faqs');
    }

    /**
     * Delete FAQ
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $faq = $this->faqModel->find($id);

        if (!$faq) {
            return $this->jsonError('FAQ tidak ditemukan', 404);
        }

        $this->faqModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}