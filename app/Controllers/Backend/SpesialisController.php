<?php

namespace App\Controllers\Backend;

use App\Models\Spesialis;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class SpesialisController extends BaseController
{
    protected $spesialis;

    public function __construct()
    {
        $this->spesialis = new Spesialis();
    }

    public function index()
    {
        $data['title'] = 'Spesialis';

        return view('backend/spesialis/index', $data);
    }

    public function create()
    {
        return view('backend/spesialis/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->spesialis->select('idspesialis,gelar,nama,status');


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

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idspesialis . '\',\'' . $row->gelar . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idspesialis . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $gelar = $this->request->getVar('gelar');
        $nama = $this->request->getVar('nama');
        $status = $this->request->getVar('status');

        $rules = $this->validate([
            'gelar' => [
                'label' => 'Spesialis',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'nama' => [
                'label' => 'Nama Spesialis',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_gelar' => $validation->getError('gelar'),
                'error_nama' => $validation->getError('nama'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->spesialis->insert([
                'gelar' => $gelar,
                'nama' => $nama,
                // jika statusnya kosong maka akan di set default menjadi Y
                'status' => $status ? $status : 'Y',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata('success', 'Data Spesialis Berhasil Di Tambahkan');
            return redirect()->to('/spesialis');
        }
    }

    public function edit($id = null)
    {
        $data['spesialis'] = $this->spesialis->find($id);
        return view('backend/spesialis/edit', $data);
    }

    public function update()
    {

        $idSpesialis = $this->request->getVar('idspesialis');
        $gelar = $this->request->getVar('gelar');
        $nama = $this->request->getVar('nama');
        $status = $this->request->getVar('status');

        $rules = $this->validate([
            'gelar' => [
                'label' => 'Spesialis',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'nama' => [
                'label' => 'Nama Spesialis',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_gelar' => $validation->getError('gelar'),
                'error_nama' => $validation->getError('nama'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $data = [
                'gelar' => $gelar,
                'nama' => $nama,
                'status' => $status ? $status : 'Y',
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->spesialis->update($idSpesialis, $data);

            session()->setFlashdata('success', 'Data Spesialis Berhasil Di Update');
            return redirect()->to('/spesialis');
        }
    }




    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $gelar = $this->spesialis->find($id);

            if ($gelar) {
                $this->spesialis->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
