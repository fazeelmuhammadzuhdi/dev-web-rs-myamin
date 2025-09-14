<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\Penunjang;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PenunjangController extends BaseController
{
    protected $penunjang;

    public function __construct()
    {
        $this->penunjang = new Penunjang();
    }

    public function index()
    {

        $data['title'] = 'Penunjang';
        return view('backend/penunjang/index', $data);
    }

    public function create()
    {
        return view('backend/penunjang/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->penunjang->select('idpenunjang,nama,keterangan,status')->orderBy('nama', 'ASC');
            return DataTable::of($builder)
                ->edit('status', function ($row) {
                    if ($row->status == 'Y') {
                        return '<span class="badge badge-success">Aktif</span>';
                    } else {
                        return '<span class="badge badge-warning">Tidak Aktif</span>';
                    }
                })
                ->edit('keterangan', function ($row) {
                    if ($row->keterangan) {
                        $doc = new DOMDocument();
                        @$doc->loadHTML($row->keterangan);

                        $text =  $doc->textContent;

                        $text =  limit_words($text, 20);

                        return $text;
                    }
                    return '-';
                })

                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idpenunjang . '\',\'' . $row->nama . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idpenunjang . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $keterangan = $this->request->getVar('keterangan');
        $nama = $this->request->getVar('nama');
        $status = $this->request->getVar('status');

        $rules = $this->validate([
            'keterangan' => [
                'label' => 'Penunjang',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'nama' => [
                'label' => 'Nama Penunjang',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_keterangan' => $validation->getError('keterangan'),
                'error_nama' => $validation->getError('nama'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->penunjang->insert([
                'keterangan' => $keterangan,
                'nama' => $nama,
                'status' => $status ? $status : 'Y',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data Penunjang Berhasil Di Tambahkan');
            return redirect()->to('/penunjang');
        }
    }

    public function edit($id = null)
    {
        $data['penunjang'] = $this->penunjang->find($id);
        return view('backend/penunjang/edit', $data);
    }

    public function update()
    {

        $idPenunjang = $this->request->getVar('idpenunjang');
        $keterangan = $this->request->getVar('keterangan');
        $nama = $this->request->getVar('nama');
        $status = $this->request->getVar('status');

        $rules = $this->validate([
            'keterangan' => [
                'label' => 'Keterangan Penunjang',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'nama' => [
                'label' => 'Nama Penunjang',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_keterangan' => $validation->getError('keterangan'),
                'error_nama' => $validation->getError('nama'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $data = [
                'keterangan' => $keterangan,
                'nama' => $nama,
                'status' => $status ? $status : 'Y',
            ];

            $this->penunjang->update($idPenunjang, $data);

            session()->setFlashdata('success', 'Data Penunjang Berhasil Di Update');
            return redirect()->to('/penunjang');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $keterangan = $this->penunjang->find($id);

            if ($keterangan) {
                $this->penunjang->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
