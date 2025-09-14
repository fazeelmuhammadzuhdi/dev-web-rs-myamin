<?php

namespace App\Controllers\Backend;

use App\Models\FileDownload;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * FileDownloadController handles file download management functionality
 * 
 * This controller manages file downloads including creation, editing, deletion, and display
 * with file type detection, icon display, and comprehensive file handling.
 */
class FileDownloadController extends BaseController
{
    // Constants for better maintainability
    private const MAX_FILE_SIZE = 2048; // 2MB
    private const MAX_UPDATE_FILE_SIZE = 4096; // 4MB for updates
    
    // Model instance
    private FileDownload $fileDownloadModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->fileDownloadModel = new FileDownload();
    }

    /**
     * Display file download index page
     */
    public function index()
    {
        $data = ['title' => 'Filedownload'];
        return view('backend/filedownload/index', $data);
    }

    /**
     * Display file download creation form
     */
    public function create()
    {
        return view('backend/filedownload/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->fileDownloadModel->select('idfile,tanggalfile,keteranganfile,uploadfile');

        return DataTable::of($builder)
            ->edit('uploadfile', function ($row) {
                return $this->formatFileColumn($row->uploadfile);
            })
            ->edit('keteranganfile', function ($row) {
                return $this->formatDescriptionColumn($row->keteranganfile);
            })
            ->edit('tanggalfile', function ($row) {
                return date('d M Y, H:i:s', strtotime($row->tanggalfile));
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idfile);
            }, 'last')
            ->toJson();
    }

    /**
     * Format file column with appropriate icon
     */
    private function formatFileColumn(?string $uploadfile): string
    {
        if (!$uploadfile) {
            return '';
        }

        $fileType = strtolower(pathinfo($uploadfile, PATHINFO_EXTENSION));
        $iconPath = $this->getFileIcon($fileType);
        $fileUrl = base_url('filedownload/' . $uploadfile);

        return '<a href="' . $fileUrl . '" target="_blank" rel="noopener noreferrer">
                    <img src="' . $iconPath . '" width="100" height="80" alt="' . esc($fileType) . ' file">
                </a>';
    }

    /**
     * Get file icon based on file type
     */
    private function getFileIcon(string $fileType): string
    {
        $iconMap = [
            'pdf' => 'filetype/pdf.png',
            'doc' => 'filetype/word.png',
            'docx' => 'filetype/word.png',
            'rar' => 'filetype/archive.png',
            'zip' => 'filetype/archive.png',
            '7z' => 'filetype/archive.png',
            'png' => 'filetype/image.png',
            'jpg' => 'filetype/image.png',
            'jpeg' => 'filetype/image.png',
            'gif' => 'filetype/image.png',
            'xls' => 'filetype/excel.png',
            'xlsx' => 'filetype/excel.png',
            'ppt' => 'filetype/powerpoint.png',
            'pptx' => 'filetype/powerpoint.png',
            'txt' => 'filetype/text.png',
        ];

        return base_url($iconMap[$fileType] ?? 'filetype/unknown.png');
    }

    /**
     * Format description column
     */
    private function formatDescriptionColumn(?string $keteranganfile): string
    {
        if ($keteranganfile) {
            // Strip HTML tags and limit length
            $text = strip_tags($keteranganfile);
            return strlen($text) > 100 ? substr($text, 0, 100) . '...' : $text;
        }
        
        return '-';
    }

    /**
     * Format action buttons
     */
    private function formatActionButtons(int $id): string
    {
        return '<div class="d-flex" role="group">
            <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $id . '\')">
                <i class="feather icon-trash-2"></i>
            </button>
            <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $id . '\')">
                <i class="feather icon-edit"></i>
            </button>
        </div>';
    }

    /**
     * Get file download validation rules
     */
    private function getFileDownloadValidationRules(bool $requireFile = false): array
    {
        $rules = [
            'keteranganfile' => [
                'label' => 'Keterangan File',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan file harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ]
        ];

        if ($requireFile) {
            $rules['uploadfile'] = [
                'label' => 'Upload File',
                'rules' => 'uploaded[uploadfile]|max_size[uploadfile,' . self::MAX_FILE_SIZE . ']',
                'errors' => [
                    'uploaded' => 'File harus diupload',
                    'max_size' => 'Ukuran file maksimum ' . self::MAX_FILE_SIZE . 'KB'
                ]
            ];
        } else {
            $rules['uploadfile'] = [
                'label' => 'Upload File',
                'rules' => 'max_size[uploadfile,' . self::MAX_UPDATE_FILE_SIZE . ']',
                'errors' => [
                    'max_size' => 'Ukuran file maksimum ' . self::MAX_UPDATE_FILE_SIZE . 'KB'
                ]
            ];
        }

        return $rules;
    }

    /**
     * Process file upload
     */
    private function processFileUpload(): ?string
    {
        $file = $this->request->getFile('uploadfile');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $namaFile = "Filedownload_" . $file->getRandomName();
            $file->move(FCPATH . 'filedownload', $namaFile);
            
            return $namaFile;
        }
        
        return null;
    }

    /**
     * Delete old file
     */
    private function deleteOldFile(string $filePath): void
    {
        if ($filePath && file_exists(FCPATH . 'filedownload/' . $filePath)) {
            $this->deleteFile('filedownload/' . $filePath);
        }
    }

    /**
     * Save new file download
     */
    public function save()
    {
        $data = $this->getFormData(['keteranganfile']);

        $rules = $this->getFileDownloadValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['uploadfile', 'keteranganfile']);
        }

        $newFile = $this->processFileUpload();
        if (!$newFile) {
            session()->setFlashdata('error_uploadfile', 'File harus diupload');
            return redirect()->back()->withInput();
        }

        $this->fileDownloadModel->insert([
            'uploadfile' => $newFile,
            'keteranganfile' => $data['keteranganfile'],
            'tanggalfile' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data File Download Berhasil Ditambahkan', '/filedownloads');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['filedownloads' => $this->fileDownloadModel->find($id)];
        return view('backend/filedownload/edit', $data);
    }

    /**
     * Update existing file download
     */
    public function update()
    {
        $data = $this->getFormData(['idfile', 'keteranganfile']);
        $idfile = $data['idfile'];

        $uploadfile = $this->request->getFile('uploadfile');
        $requireFile = $uploadfile->isValid() && !$uploadfile->hasMoved();
        
        $rules = $this->getFileDownloadValidationRules(false);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['uploadfile', 'keteranganfile']);
        }

        $updateData = [
            'keteranganfile' => $data['keteranganfile']
        ];

        // Handle file update
        if ($requireFile) {
            $existingFile = $this->fileDownloadModel->find($idfile);
            if ($existingFile && $existingFile['uploadfile']) {
                $this->deleteOldFile($existingFile['uploadfile']);
            }
            
            $newFile = $this->processFileUpload();
            if ($newFile) {
                $updateData['uploadfile'] = $newFile;
            }
        }

        $this->fileDownloadModel->update($idfile, $updateData);

        return $this->setSuccessMessage('Data Filedownload Berhasil Di Update', '/filedownloads');
    }

    /**
     * Delete file download
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $fileDownload = $this->fileDownloadModel->find($id);

        if (!$fileDownload) {
            return $this->jsonError('Data file download tidak ditemukan', 404);
        }

        // Delete associated file
        if ($fileDownload['uploadfile']) {
            $this->deleteOldFile($fileDownload['uploadfile']);
        }

        $this->fileDownloadModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}