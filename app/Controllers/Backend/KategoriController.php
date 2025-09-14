<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Kategori;
use Hermawan\DataTables\DataTable;

class KategoriController extends BaseController
{
    protected $kategori;

    public function __construct()
    {
        $this->kategori = new Kategori();
        helper('slug');
    }

    public function index()
    {
        $data['title'] = 'Kategori';

        return view('backend/kategori/index', $data);
    }

    public function create()
    {
        return view('backend/kategori/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->kategori->select('idkategori,title,slug,status');


            return DataTable::of($builder)
                ->edit('status', function ($row) {
                    if ($row->status == 'Y') {
                        return '<span class="badge badge-success">Aktif</span>';
                    } else {
                        return '<span class="badge badge-warning">Tidak Aktif</span>';
                    }
                })
                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $row->idkategori . '\',\'' . $row->title . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $row->idkategori . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $idKategori = $this->request->getVar('idkategori');
        $title = $this->request->getVar('title');
        $status = $this->request->getVar('status');

        $rules = $this->validate([
            'idkategori' => [
                'label' => 'Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'title' => [
                'label' => 'Judul Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_kategori' => $validation->getError('idkategori'),
                'error_title' => $validation->getError('title'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->kategori->insert([
                'idkategori' => $idKategori,
                'title' => $title,
                // jika statusnya kosong maka akan di set default menjadi Y
                'status' => $status ? $status : 'Y',
                'slug' => createSlug($title),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata('success', 'Data Kategori Berhasil Di Tambahkan');
            return redirect()->to('/kategoris');
        }
    }

    public function edit($id = null)
    {
        $data['kategori'] = $this->kategori->find($id);
        return view('backend/kategori/edit', $data);
    }



    public function update()
    {
        $idKategori = $this->request->getVar('idkategori');
        $oldIdKategori = $this->request->getVar('old_idkategori'); // hidden field to store original ID
        $title = $this->request->getVar('title');
        $status = $this->request->getVar('status');

        $rules = $this->validate([
            'idkategori' => [
                'label' => 'Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'title' => [
                'label' => 'Judul Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_kategori' => $validation->getError('idkategori'),
                'error_title' => $validation->getError('title'),
            ]);
            return redirect()->back()->withInput();
        } else {
            // Update non-key fields first
            $data = [
                'title' => $title,
                'status' => $status ? $status : 'Y',
                'slug' => createSlug($title),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $this->kategori->update($oldIdKategori, $data);

            if ($idKategori !== $oldIdKategori) {
                $this->kategori->query("UPDATE kategori SET idkategori = ? WHERE idkategori = ?", [$idKategori, $oldIdKategori]);
            }

            session()->setFlashdata('success', 'Data Kategori Berhasil Di Update');
            return redirect()->to('/kategoris');
        }
    }


    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $idKategori = $this->kategori->find($id);

            if ($idKategori) {
                $this->kategori->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
