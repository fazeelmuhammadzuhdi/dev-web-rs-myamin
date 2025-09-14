<?php

namespace App\Controllers\Backend;

use App\Models\IndikatorMutu;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class IndikatorMutuController extends BaseController
{
    protected $indikatormutu;

    public function __construct()
    {
        $this->indikatormutu = new IndikatorMutu();
    }

    public function index()
    {
        $data['title'] = 'Indikator Mutu';
        return view('backend/indikatormutu/index', $data);
    }

    public function create()
    {
        return view('backend/indikatormutu/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->indikatormutu->select('idindikatormutu,nama,status');


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

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idindikatormutu . '\',\'' . $row->nama . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idindikatormutu . '\')">
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
                'label' => 'Nama Indikatormutu',
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
            $this->indikatormutu->insert([
                'nama' => $nama,
                'status' => $status ? $status : 'Y',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data Indikator mutu Berhasil Di Tambahkan');
            return redirect()->to('/indikatormutu');
        }
    }

    public function edit($id = null)
    {
        $data['indikatormutu'] = $this->indikatormutu->find($id);
        return view('backend/indikatormutu/edit', $data);
    }

    public function update()
    {

        $idIndikatormutu = $this->request->getVar('idindikatormutu');
        $nama = $this->request->getVar('nama');
        $status = $this->request->getVar('status');

        $rules = $this->validate([


            'nama' => [
                'label' => 'Nama Indikatormutu',
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

            $this->indikatormutu->update($idIndikatormutu, $data);

            session()->setFlashdata('success', 'Data Indikator mutu Berhasil Di Update');
            return redirect()->to('/indikatormutu');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $gelar = $this->indikatormutu->find($id);

            if ($gelar) {
                $this->indikatormutu->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
