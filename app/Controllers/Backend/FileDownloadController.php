<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\FileDownload;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class FileDownloadController extends BaseController
{
    protected $filedownload;

    public function __construct()
    {
        $this->filedownload = new FileDownload();
    }

    public function index()
    {
        $data['title'] = 'Filedownload';
        return view('backend/filedownload/index', $data);
    }

    public function create()
    {
        return view('backend/filedownload/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->filedownload->select('idfile,tanggalfile,keteranganfile,uploadfile');


            return DataTable::of($builder)
                ->edit('uploadfile', function ($row) {
                    if ($row->uploadfile !== null) {
                        $fileType = pathinfo($row->uploadfile, PATHINFO_EXTENSION);
                        $iconPath = '';
                        switch (strtolower($fileType)) {
                            case 'pdf':
                                $iconPath = base_url('filetype/pdf.png');
                                break;
                            case 'doc':
                            case 'docx':
                                $iconPath = base_url('filetype/word.png');
                                break;
                            case 'rar':
                                $iconPath = base_url('filetype/archive.png');
                                break;
                            case 'png':
                                $iconPath = base_url('filetype/image.png');
                                break;
                            case 'jpg':
                            case 'jpeg':
                                $iconPath = base_url('filetype/image.png');
                                break;
                            default:
                                $iconPath = base_url('filetype/unknown.png'); // Ikon default jika tipe file tidak dikenali
                                break;
                        }

                        $fileUrl = base_url('filedownload/' . $row->uploadfile);

                        return '<a href="' . $fileUrl . '" target="_blank">
                                    <img src="' . $iconPath . '" width="100" height="80">
                                </a>';
                    } else {
                        return '';
                    }
                })
                ->edit('keteranganfile', function ($row) {
                    if ($row->keteranganfile) {
                        $doc = new DOMDocument();
                        @$doc->loadHTML($row->keteranganfile);
                        return $doc->textContent; // Menghapus tag HTML
                    }
                    return '-';
                })
                ->edit('tanggalfile', function ($row) {
                    return date('d M Y, H:i:s', strtotime($row->tanggalfile)); // Format tanggal sesuai kebutuhan
                })
                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" judul="Hapus Data" onclick="hapus(\'' . $row->idfile . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" judul="Edit Data" onclick="edit(\'' . $row->idfile . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $keteranganfile = $this->request->getVar('keteranganfile');

        $rules = $this->validate([


            'keteranganfile' => [
                'label' => 'Keterangan File',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'uploadfile' => [
                'label' => 'Uploadfile Filedownload',
                'rules' => 'uploaded[uploadfile]|max_size[uploadfile,2048]',
                'errors' => [
                    'uploaded' => '{field} tidak boleh kosong',
                    'max_size' => 'Ukuran {field} maksimum 2MB',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_uploadfile' => $validation->getError('uploadfile'),
                'error_keteranganfile' => $validation->getError('keteranganfile'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $fileFoto = $this->request->getFile('uploadfile');

            $namaFoto = "Filedownload" . '_' . $fileFoto->getRandomName();
            // Pindahkan file foto ke folder tujuan (public/filedownload)
            $fileFoto->move(FCPATH . 'filedownload', $namaFoto);

            $this->filedownload->insert([
                'uploadfile' => $namaFoto,
                'keteranganfile' => $keteranganfile,
                'tanggalfile' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data File Download Berhasil Di Tambahkan');
            return redirect()->to('/filedownloads');
        }
    }

    public function edit($id = null)
    {
        $data['filedownloads'] = $this->filedownload->find($id);
        return view('backend/filedownload/edit', $data);
    }

    public function update()
    {

        $idfile = $this->request->getVar('idfile');
        $uploadfile = $this->request->getFile('uploadfile');
        $keteranganfile = $this->request->getVar('keteranganfile');


        $rules = [

            'keteranganfile' => [
                'label' => 'Keterangan File',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ];

        if ($uploadfile->isValid() && !$uploadfile->hasMoved()) {
            // Validasi uploadfile
            $rules['uploadfile'] = 'uploaded[uploadfile]|max_size[uploadfile,4096]';
        }

        $validation = \Config\Services::validation();
        $isValid = $validation->withRequest($this->request)->setRules($rules)->run();

        if (!$isValid) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_uploadfile' => $validation->getError('uploadfile'),
                'error_keteranganfile' => $validation->getError('keteranganfile'),
            ]);

            return redirect()->back()->withInput();
        } else {

            // Menghapus foto lama jika ada foto baru diunggah
            if ($uploadfile->isValid() && !$uploadfile->hasMoved()) {
                $filedownload = $this->filedownload->find($idfile);
                if ($filedownload['uploadfile'] !== null) {
                    $oldFotoPath = FCPATH . 'filedownload/' . $filedownload['uploadfile'];
                    if (file_exists($oldFotoPath)) {
                        unlink($oldFotoPath);
                    }
                }

                $newFotoName = "Filedownload" . '_' . $uploadfile->getRandomName();
                $uploadfile->move(FCPATH . 'filedownload', $newFotoName);

                // Update data filedownload dengan foto baru
                $this->filedownload->update($idfile, [
                    'uploadfile' => $newFotoName,
                    'keteranganfile' => $keteranganfile
                ]);
            } else {
                // Jika tidak ada foto baru diunggah, update data filedownload tanpa foto
                $this->filedownload->update($idfile, [
                    'keteranganfile' => $keteranganfile
                ]);
            }

            session()->setFlashdata('success', 'Data Filedownload Berhasil Di Update');
            return redirect()->to('/filedownloads');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $cekReferensi = $this->filedownload->find($id);

            if ($cekReferensi) {
                // Menghapus foto jika ada
                if ($cekReferensi['uploadfile'] !== null) {
                    $fotoPath = FCPATH . 'filedownload/' . $cekReferensi['uploadfile'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }

                // Menghapus data dari database
                $this->filedownload->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
