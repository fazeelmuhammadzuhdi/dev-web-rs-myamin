<?php

namespace App\Controllers\PPID;

use DOMDocument;
use App\Models\PagesPPID;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class PagesController extends BaseController
{
    protected $pages;

    public function __construct()
    {
        $this->pages = new PagesPPID();
        helper('slug');
    }

    public function index()
    {
        $data['title'] = 'Pages';

        return view('backend/pages/index', $data);
    }

    public function create()
    {
        return view('backend/pages/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->pages->select('idpages,title,konten,status');


            return DataTable::of($builder)
                ->edit('status', function ($row) {
                    if ($row->status == 'Y') {
                        return '<span class="badge badge-success">Aktif</span>';
                    } else {
                        return '<span class="badge badge-danger">Tidak Aktif</span>';
                    }
                })

                ->edit('konten', function ($row) {
                    if ($row->konten) {
                        $doc = new DOMDocument();
                        @$doc->loadHTML($row->konten);
                        return $doc->textContent;
                    }
                    return '-';
                })
                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $row->idpages . '\',\'' . $row->title . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $row->idpages . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $title = $this->request->getVar('title');
        $konten = $this->request->getVar('konten');
        $status = $this->request->getVar('status');

        $rules = $this->validate([
            'konten' => [
                'label' => 'Konten Pages',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'title' => [
                'label' => 'Judul Pages',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'gambar' => [
                'label' => 'Gambar Banner',
                'rules' => 'uploaded[gambar]|max_size[gambar,1024]|mime_in[gambar,image/jpeg,image/png,image/jpg]',
                'errors' => [
                    'uploaded' => '{field} tidak boleh kosong',
                    'max_size' => 'Ukuran {field} maksimum 1MB',
                    'mime_in' => 'Format {field} harus JPEG ,PNG atau JPG'
                ]
            ],


        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_pages' => $validation->getError('idpages'),
                'error_title' => $validation->getError('title'),
                'error_gambar' => $validation->getError('gambar'),

            ]);
            return redirect()->back()->withInput();
        } else {
            $fileFoto = $this->request->getFile('gambar');

            $namaFoto = "ppid" . '_' . $fileFoto->getRandomName();
            // Pindahkan file foto ke folder tujuan (public/banner)
            $fileFoto->move(FCPATH . 'frontend/images/ppid', $namaFoto);

            $this->pages->insert([
                'title' => $title,
                'konten' => $konten,
                'slug' => createSlug($title),
                'status' => $status ? $status : 'Y',
                'created_at' => date('Y-m-d H:i:s'),
                'gambar' => $namaFoto,
            ]);

            session()->setFlashdata('success', 'Data Pages Berhasil Di Tambahkan');
            return redirect()->to('/page');
        }
    }

    public function edit($id = null)
    {
        $data['page'] = $this->pages->find($id);
        return view('backend/pages/edit', $data);
    }



    public function update()
    {
        $idPages = $this->request->getVar('idpages');
        $konten = $this->request->getVar('konten');
        $title = $this->request->getVar('title');
        $status = $this->request->getVar('status');
        $gambar = $this->request->getFile('gambar');

        $rules = $this->validate([
            'konten' => [
                'label' => 'Konten Pages',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'title' => [
                'label' => 'Judul Pages',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_konten' => $validation->getError('konten'),
                'error_title' => $validation->getError('title'),
            ]);
            return redirect()->back()->withInput();
        } else {
            // Menghapus foto lama jika ada foto baru diunggah
            if ($gambar->isValid() && !$gambar->hasMoved()) {
                $banner = $this->pages->find($idPages);
                if ($banner['gambar'] !== null) {
                    $oldFotoPath = FCPATH . 'frontend/images/ppid/' . $banner['gambar'];
                    if (file_exists($oldFotoPath)) {
                        unlink($oldFotoPath);
                    }
                }

                $newFotoName = "ppid" . '_' . $gambar->getRandomName();
                $gambar->move(FCPATH . 'frontend/images/ppid/', $newFotoName);

                $this->pages->update($idPages, [
                    'title' => $title,
                    'konten' => $konten,
                    'status' => $status ? $status : 'Y',
                    'slug' => createSlug($title),
                    'gambar' => $newFotoName,
                ]);
            } else {
                // Jika tidak ada foto baru diunggah, update data banner tanpa foto
                $this->pages->update($idPages, [
                    'title' => $title,
                    'konten' => $konten,
                    'status' => $status ? $status : 'Y',
                    'slug' => createSlug($title),
                ]);
            }



            session()->setFlashdata('success', "Data Berhasil Di Update");
            return redirect()->to('/page');
        }
    }


    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $idPages = $this->pages->find($id);

            if ($idPages) {
                // Menghapus foto jika ada
                if ($idPages['gambar'] !== null) {
                    $fotoPath = FCPATH . 'frontend/images/ppid/' . $idPages['gambar'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }

                // Menghapus data dari database
                $this->pages->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
