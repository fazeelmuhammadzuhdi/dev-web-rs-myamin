<?php

namespace App\Controllers\Backend;

use App\Models\Rawat;
use App\Models\TempatTidur;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TempatTidurController extends BaseController
{
    protected $tempattidur;
    protected $rawat;

    public function __construct()
    {
        $this->tempattidur = new TempatTidur();
        $this->rawat = new Rawat();
    }

    public function index()
    {
        $data['title'] = 'Tempat Tidur';

        return view('backend/tempattidur/index', $data);
    }

    public function create()
    {
        $data['rawat'] = $this->rawat->findAll();

        return view('backend/tempattidur/create', $data);
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {

            $builder = $this->tempattidur->getTempatTidur();
            // $builder = $this->tempattidur->select('tempat_tidur.idtempattidur,tempat_tidur.vip_isi,tempat_tidur.vip_kosong,tempat_tidur.utama_isi,tempat_tidur.utama_kosong,rawat.nama')
            //     ->join('rawat', 'rawat.idrawat = tempat_tidur.rawat_id');


            return DataTable::of($builder)

                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idtempattidur . '\',\'' . $row->vip_isi . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idtempattidur . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $idrawat = $this->request->getVar('rawat_id');
        $vip_isi = $this->request->getVar('vip_isi');
        $vip_kosong = $this->request->getVar('vip_kosong');
        $utama_isi = $this->request->getVar('utama_isi');
        $utama_kosong = $this->request->getVar('utama_kosong');
        $kelas1_isi = $this->request->getVar('kelas1_isi');
        $kelas1_kosong = $this->request->getVar('kelas1_kosong');
        $kelas2_isi = $this->request->getVar('kelas2_isi');
        $kelas2_kosong = $this->request->getVar('kelas2_kosong');
        $kelas3_isi = $this->request->getVar('kelas3_isi');
        $kelas3_kosong = $this->request->getVar('kelas3_kosong');

        $rules = $this->validate([
            'rawat_id' => [
                'label' => 'Nama Ruangan Rawat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'vip_isi' => [
                'label' => 'Tempat Tidur VIP Isi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'vip_kosong' => [
                'label' => 'Tempat Tidur VIP Kosong',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'utama_isi' => [
                'label' => 'Tempat Tidur utama Isi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'utama_kosong' => [
                'label' => 'Tempat Tidur utama Kosong',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'kelas1_isi' => [
                'label' => 'Tempat Tidur kelas1 Isi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'kelas1_kosong' => [
                'label' => 'Tempat Tidur kelas1 Kosong',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'kelas2_isi' => [
                'label' => 'Tempat Tidur kelas2 Isi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'kelas2_kosong' => [
                'label' => 'Tempat Tidur kelas2 Kosong',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'kelas3_isi' => [
                'label' => 'Tempat Tidur kelas3 Isi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'kelas3_kosong' => [
                'label' => 'Tempat Tidur kelas3 Kosong',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_rawat_id' => $validation->getError('rawat_id'),
                'error_vip_isi' => $validation->getError('vip_isi'),
                'error_vip_kosong' => $validation->getError('vip_kosong'),
                'error_utama_isi' => $validation->getError('utama_isi'),
                'error_utama_kosong' => $validation->getError('utama_kosong'),
                'error_kelas1_isi' => $validation->getError('kelas1_isi'),
                'error_kelas1_kosong' => $validation->getError('kelas1_kosong'),
                'error_kelas2_isi' => $validation->getError('kelas2_isi'),
                'error_kelas2_kosong' => $validation->getError('kelas2_kosong'),
                'error_kelas3_isi' => $validation->getError('kelas3_isi'),
                'error_kelas3_kosong' => $validation->getError('kelas3_kosong'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->tempattidur->insert([
                'rawat_id' => $idrawat,
                'vip_isi' => $vip_isi,
                'vip_kosong' => $vip_kosong,
                'utama_isi' => $utama_isi,
                'utama_kosong' => $utama_kosong,
                'kelas1_isi' => $kelas1_isi,
                'kelas1_kosong' => $kelas1_kosong,
                'kelas2_isi' => $kelas2_isi,
                'kelas2_kosong' => $kelas2_kosong,
                'kelas3_isi' => $kelas3_isi,
                'kelas3_kosong' => $kelas3_kosong,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata('success', 'Data Tempattidur Berhasil Di Tambahkan');
            return redirect()->to('/tempattidur');
        }
    }

    public function edit($id = null)
    {
        $data['rawat'] = $this->rawat->findAll();
        $data['tempattidur'] = $this->tempattidur->find($id);
        return view('backend/tempattidur/edit', $data);
    }

    public function update()
    {

        $idTempatTidur = $this->request->getVar('idtempattidur');
        $idrawat = $this->request->getVar('rawat_id');
        $vip_isi = $this->request->getVar('vip_isi');
        $vip_kosong = $this->request->getVar('vip_kosong');
        $utama_isi = $this->request->getVar('utama_isi');
        $utama_kosong = $this->request->getVar('utama_kosong');
        $kelas1_isi = $this->request->getVar('kelas1_isi');
        $kelas1_kosong = $this->request->getVar('kelas1_kosong');
        $kelas2_isi = $this->request->getVar('kelas2_isi');
        $kelas2_kosong = $this->request->getVar('kelas2_kosong');
        $kelas3_isi = $this->request->getVar('kelas3_isi');
        $kelas3_kosong = $this->request->getVar('kelas3_kosong');

        $rules = $this->validate([
            'rawat_id' => [
                'label' => 'Nama Ruangan Rawat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'vip_isi' => [
                'label' => 'Tempat Tidur VIP Isi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'vip_kosong' => [
                'label' => 'Tempat Tidur VIP Kosong',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'utama_isi' => [
                'label' => 'Tempat Tidur utama Isi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'utama_kosong' => [
                'label' => 'Tempat Tidur utama Kosong',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'kelas1_isi' => [
                'label' => 'Tempat Tidur kelas1 Isi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'kelas1_kosong' => [
                'label' => 'Tempat Tidur kelas1 Kosong',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'kelas2_isi' => [
                'label' => 'Tempat Tidur kelas2 Isi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'kelas2_kosong' => [
                'label' => 'Tempat Tidur kelas2 Kosong',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'kelas3_isi' => [
                'label' => 'Tempat Tidur kelas3 Isi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'kelas3_kosong' => [
                'label' => 'Tempat Tidur kelas3 Kosong',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_rawat_id' => $validation->getError('rawat_id'),
                'error_vip_isi' => $validation->getError('vip_isi'),
                'error_vip_kosong' => $validation->getError('vip_kosong'),
                'error_utama_isi' => $validation->getError('utama_isi'),
                'error_utama_kosong' => $validation->getError('utama_kosong'),
                'error_kelas1_isi' => $validation->getError('kelas1_isi'),
                'error_kelas1_kosong' => $validation->getError('kelas1_kosong'),
                'error_kelas2_isi' => $validation->getError('kelas2_isi'),
                'error_kelas2_kosong' => $validation->getError('kelas2_kosong'),
                'error_kelas3_isi' => $validation->getError('kelas3_isi'),
                'error_kelas3_kosong' => $validation->getError('kelas3_kosong'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $data = [
                'rawat_id' => $idrawat,
                'vip_isi' => $vip_isi,
                'vip_kosong' => $vip_kosong,
                'utama_isi' => $utama_isi,
                'utama_kosong' => $utama_kosong,
                'kelas1_isi' => $kelas1_isi,
                'kelas1_kosong' => $kelas1_kosong,
                'kelas2_isi' => $kelas2_isi,
                'kelas2_kosong' => $kelas2_kosong,
                'kelas3_isi' => $kelas3_isi,
                'kelas3_kosong' => $kelas3_kosong,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->tempattidur->update($idTempatTidur, $data);

            session()->setFlashdata('success', 'Data Tempat Tidur Berhasil Di Tambahkan');
            return redirect()->to('/tempattidur');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $idrawat = $this->tempattidur->find($id);

            if ($idrawat) {
                $this->tempattidur->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
