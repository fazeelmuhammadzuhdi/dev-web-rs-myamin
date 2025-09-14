<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\Fasilitas;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class FasilitasController extends BaseController
{
    protected $fasilitas;

    public function __construct()
    {
        $this->fasilitas = new Fasilitas();
    }

    public function index()
    {
        $data['title'] = 'Fasilitas';

        return view('backend/fasilitas/index', $data);
    }

    public function create()
    {
        return view('backend/fasilitas/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->fasilitas->select('idfasilitas,nama,keterangan,status');
            return DataTable::of($builder)
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

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idfasilitas . '\',\'' . $row->nama . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idfasilitas . '\')">
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
        $keterangan = $this->request->getVar('keterangan');

        $rules = $this->validate([

            'nama' => [
                'label' => 'Nama Fasilitas',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'keterangan' => [
                'label' => 'Keterangan Fasilitas',
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
                'error_keterangan' => $validation->getError('keterangan'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->fasilitas->insert([
                'nama' => $nama,
                'keterangan' => $keterangan,
                'status' => $status ? $status : 'Y',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data Fasilitas Berhasil Di Tambahkan');
            return redirect()->to('/fasilitasumum');
        }
    }

    public function edit($id = null)
    {
        $data['fasilitas'] = $this->fasilitas->find($id);
        return view('backend/fasilitas/edit', $data);
    }

    public function update()
    {

        $idFasilitas = $this->request->getVar('idfasilitas');
        $nama = $this->request->getVar('nama');
        $status = $this->request->getVar('status');
        $keterangan = $this->request->getVar('keterangan');

        $rules = $this->validate([

            'nama' => [
                'label' => 'Nama Fasilitas',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'keterangan' => [
                'label' => 'Keterangan Fasilitas',
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
                'error_keterangan' => $validation->getError('keterangan'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $data = [
                'nama' => $nama,
                'keterangan' => $keterangan,
                'status' => $status ? $status : 'Y',
            ];

            $this->fasilitas->update($idFasilitas, $data);

            session()->setFlashdata('success', 'Data Fasilitas Berhasil Di Update');
            return redirect()->to('/fasilitasumum');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $keterangan = $this->fasilitas->find($id);

            if ($keterangan) {
                $this->fasilitas->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
