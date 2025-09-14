<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\Manajemen;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class ManajemenController extends BaseController
{
    protected $manajemen;

    public function __construct()
    {
        $this->manajemen = new Manajemen();
    }

    public function index()
    {
        $data['title'] = 'Manajemen';

        return view('backend/manajemen/index', $data);
    }

    public function create()
    {
        return view('backend/manajemen/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->manajemen->select('idmanajemen,nama,status');
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

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idmanajemen . '\',\'' . $row->nama . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idmanajemen . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $nama = $this->request->getVar('nama');
        $status = $this->request->getVar('status');

        $rules = $this->validate([

            'nama' => [
                'label' => 'Nama Manajemen',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_nama' => $validation->getError('nama'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->manajemen->insert([
                'nama' => $nama,
                'status' => $status ? $status : 'Y',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data Manajemen Berhasil Di Tambahkan');
            return redirect()->to('/manajemen');
        }
    }

    public function edit($id = null)
    {
        $data['manajemen'] = $this->manajemen->find($id);
        return view('backend/manajemen/edit', $data);
    }

    public function update()
    {

        $idManajemen = $this->request->getVar('idmanajemen');
        $nama = $this->request->getVar('nama');
        $status = $this->request->getVar('status');

        $rules = $this->validate([

            'nama' => [
                'label' => 'Nama Manajemen',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_nama' => $validation->getError('nama'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $data = [
                'nama' => $nama,
                'status' => $status ? $status : 'Y',
            ];

            $this->manajemen->update($idManajemen, $data);

            session()->setFlashdata('success', 'Data Manajemen Berhasil Di Update');
            return redirect()->to('/manajemen');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $keterangan = $this->manajemen->find($id);

            if ($keterangan) {
                $this->manajemen->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
