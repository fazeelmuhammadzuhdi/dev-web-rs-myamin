<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\Sejarah;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SejarahController extends BaseController
{
    protected $sejarah;

    public function __construct()
    {
        $this->sejarah = new Sejarah();
    }

    public function index()
    {
        $data['title'] = 'Sejarah';
        return view('backend/sejarah/index', $data);
    }

    public function create()
    {
        return view('backend/sejarah/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->sejarah->select('idsejarah,tahun,keterangan')->orderBy('tahun', 'ASC');
            return DataTable::of($builder)
                ->edit('keterangan', function ($row) {
                    if ($row->keterangan) {
                        $doc = new DOMDocument();
                        @$doc->loadHTML($row->keterangan);
                        return $doc->textContent; // Menghapus tag HTML
                    }
                    return '-';
                })

                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" tahun="Hapus Data" onclick="hapus(\'' . $row->idsejarah . '\',\'' . $row->tahun . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" tahun="Edit Data" onclick="edit(\'' . $row->idsejarah . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $tahun = $this->request->getVar('tahun');
        $keterangan = $this->request->getVar('keterangan');
        $judul = $this->request->getVar('judul');

        $rules = $this->validate([

            'tahun' => [
                'label' => 'Tahun Sejarah',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'judul' => [
                'label' => 'Judul Sejarah',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Sejarah',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],


        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_tahun' => $validation->getError('tahun'),
                'error_keterangan' => $validation->getError('keterangan'),
                'error_judul' => $validation->getError('judul'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->sejarah->insert([
                'tahun' => $tahun,
                'judul' => $judul,
                'keterangan' => $keterangan,
            ]);

            session()->setFlashdata('success', 'Data Sejarah Berhasil Di Tambahkan');
            return redirect()->to('/sejarahs');
        }
    }

    public function edit($id = null)
    {
        $data['sejarahs'] = $this->sejarah->find($id);
        return view('backend/sejarah/edit', $data);
    }

    public function update()
    {

        $idSejarah = $this->request->getVar('idsejarah');
        $tahun = $this->request->getVar('tahun');
        $keterangan = $this->request->getVar('keterangan');
        $judul = $this->request->getVar('judul');

        $rules = [

            'tahun' => [
                'label' => 'Tahun Sejarah',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'judul' => [
                'label' => 'Judul Sejarah',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Sejarah',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
        ];

        $validation = \Config\Services::validation();
        $isValid = $validation->withRequest($this->request)->setRules($rules)->run();

        if (!$isValid) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_tahun' => $validation->getError('tahun'),
                'error_keterangan' => $validation->getError('keterangan'),
                'error_judul' => $validation->getError('judul'),
            ]);

            return redirect()->back()->withInput();
        } else {

            $this->sejarah->update($idSejarah, [
                'tahun' => $tahun,
                'keterangan' => $keterangan,
                'judul' => $judul,
            ]);

            session()->setFlashdata('success', 'Data Sejarah Berhasil Di Update');
            return redirect()->to('/sejarahs');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $sj = $this->sejarah->find($id);

            if ($sj) {
                $this->sejarah->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
