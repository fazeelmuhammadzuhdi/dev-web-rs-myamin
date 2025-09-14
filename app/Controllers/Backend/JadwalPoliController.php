<?php

namespace App\Controllers\Backend;

use App\Models\Poli;
use App\Models\Dokter;
use App\Models\JadwalPoli;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use App\Models\Spesialis;

class JadwalPoliController extends BaseController
{
    protected $jadwalpoli;
    protected $dokter;
    protected $poli;
    protected $spesialis;

    public function __construct()
    {
        $this->jadwalpoli = new JadwalPoli();
        $this->dokter = new Dokter();
        $this->poli = new Poli();
        $this->spesialis = new Spesialis();
    }

    public function index()
    {
        $data['title'] = 'Jadwal Poli';

        return view('backend/jadwalpoli/index', $data);
    }

    public function create()
    {
        $data['dokter'] = $this->dokter->where('status',  'Y')->findAll();
        $data['spesialis'] = $this->spesialis->where('status',  'Y')->findAll();
        $data['poli'] = $this->poli->where('status', 'Y')->findAll();

        // return view('backend/jadwalpoli/create', $data);
        return view('backend/jadwalpoli/create', $data);
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {

            $builder = $this->jadwalpoli->getJadwalPoli();
            return DataTable::of($builder)

                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idjadwalpoli . '\',\'' . $row->nama_dokter . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idjadwalpoli . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $poli_id = $this->request->getVar('poli_id');
        $dokter_id = $this->request->getVar('dokter_id');
        $senin = $this->request->getVar('senin');
        $keterangan_senin = $this->request->getVar('keterangan_senin');
        $selasa = $this->request->getVar('selasa');
        $keterangan_selasa = $this->request->getVar('keterangan_selasa');
        $rabu = $this->request->getVar('rabu');
        $keterangan_rabu = $this->request->getVar('keterangan_rabu');
        $kamis = $this->request->getVar('kamis');
        $keterangan_kamis = $this->request->getVar('keterangan_kamis');
        $jumat = $this->request->getVar('jumat');
        $keterangan_jumat = $this->request->getVar('keterangan_jumat');
        $sabtu = $this->request->getVar('sabtu');
        $keterangan_sabtu = $this->request->getVar('keterangan_sabtu');
        $minggu = $this->request->getVar('minggu');
        $keterangan_minggu = $this->request->getVar('keterangan_minggu');

        $rules = $this->validate([
            'poli_id' => [
                'label' => 'Nama Poli',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'dokter_id' => [
                'label' => 'Nama Dokter',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            // 'senin' => [
            //     'label' => 'Jadwal Senin',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} tidak boleh kosong',
            //     ]
            // ],
            // 'keterangan_senin' => [
            //     'label' => 'Nama Dokter Hari Senin Tidak Boleh Kosong',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} tidak boleh kosong',
            //     ]
            // ],

            // 'utama_isi' => [
            //     'label' => 'Tempat Tidur utama Isi',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} tidak boleh kosong',
            //     ]
            // ],
            // 'utama_kosong' => [
            //     'label' => 'Tempat Tidur utama Kosong',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} tidak boleh kosong',
            //     ]
            // ],

            // 'kelas1_isi' => [
            //     'label' => 'Tempat Tidur kelas1 Isi',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} tidak boleh kosong',
            //     ]
            // ],
            // 'kelas1_kosong' => [
            //     'label' => 'Tempat Tidur kelas1 Kosong',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} tidak boleh kosong',
            //     ]
            // ],

            // 'kelas2_isi' => [
            //     'label' => 'Tempat Tidur kelas2 Isi',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} tidak boleh kosong',
            //     ]
            // ],
            // 'kelas2_kosong' => [
            //     'label' => 'Tempat Tidur kelas2 Kosong',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} tidak boleh kosong',
            //     ]
            // ],

            // 'kelas3_isi' => [
            //     'label' => 'Tempat Tidur kelas3 Isi',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} tidak boleh kosong',
            //     ]
            // ],
            // 'kelas3_kosong' => [
            //     'label' => 'Tempat Tidur kelas3 Kosong',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} tidak boleh kosong',
            //     ]
            // ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_poli_id' => $validation->getError('poli_id'),
                'error_dokter_id' => $validation->getError('dokter_id'),
                'error_senin' => $validation->getError('senin'),
                'error_keterangan_senin' => $validation->getError('keterangan_senin'),
                'error_selasa' => $validation->getError('selasa'),
                'error_keterangan_selasa' => $validation->getError('keterangan_selasa'),
                'error_rabu' => $validation->getError('rabu'),
                'error_keterangan_rabu' => $validation->getError('keterangan_rabu'),
                'error_kamis' => $validation->getError('kamis'),
                'error_keterangan_kamis' => $validation->getError('keterangan_kamis'),
                'error_jumat' => $validation->getError('jumat'),
                'error_keterangan_jumat' => $validation->getError('keterangan_jumat'),
                'error_sabtu' => $validation->getError('sabtu'),
                'error_keterangan_sabtu' => $validation->getError('keterangan_sabtu'),
                'error_minggu' => $validation->getError('minggu'),
                'error_keterangan_minggu' => $validation->getError('keterangan_minggu'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->jadwalpoli->insert([
                'dokter_id' => $dokter_id,
                'poli_id' => $poli_id,
                'senin' => $senin,
                'keterangan_senin' => $keterangan_senin,
                'selasa' => $selasa,
                'keterangan_selasa' => $keterangan_selasa,
                'rabu' => $rabu,
                'keterangan_rabu' => $keterangan_rabu,
                'kamis' => $kamis,
                'keterangan_kamis' => $keterangan_kamis,
                'jumat' => $jumat,
                'keterangan_jumat' => $keterangan_jumat,
                'sabtu' => $sabtu,
                'keterangan_sabtu' => $keterangan_sabtu,
                'minggu' => $minggu,
                'keterangan_minggu' => $keterangan_minggu,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data Jadwal Poli Berhasil Di Tambahkan');
            return redirect()->to('/jadwalpoli');
        }
    }

    public function edit($id = null)
    {
        $data['dokter'] = $this->dokter->where('status',  'Y')->findAll();
        $data['poli'] = $this->poli->where('status', 'Y')->findAll();
        $data['jadwalpoli'] = $this->jadwalpoli->find($id);
        $data['spesialis'] = $this->spesialis->where('status',  'Y')->findAll();
        return view('backend/jadwalpoli/edit', $data);
    }

    public function update()
    {

        $idJadwalpoli = $this->request->getVar('idjadwalpoli');
        $poli_id = $this->request->getVar('poli_id');
        $dokter_id = $this->request->getVar('dokter_id');
        $senin = $this->request->getVar('senin');
        $keterangan_senin = $this->request->getVar('keterangan_senin');
        $selasa = $this->request->getVar('selasa');
        $keterangan_selasa = $this->request->getVar('keterangan_selasa');
        $rabu = $this->request->getVar('rabu');
        $keterangan_rabu = $this->request->getVar('keterangan_rabu');
        $kamis = $this->request->getVar('kamis');
        $keterangan_kamis = $this->request->getVar('keterangan_kamis');
        $jumat = $this->request->getVar('jumat');
        $keterangan_jumat = $this->request->getVar('keterangan_jumat');
        $sabtu = $this->request->getVar('sabtu');
        $keterangan_sabtu = $this->request->getVar('keterangan_sabtu');
        $minggu = $this->request->getVar('minggu');
        $keterangan_minggu = $this->request->getVar('keterangan_minggu');

        $rules = $this->validate([
            'poli_id' => [
                'label' => 'Nama Poli',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'dokter_id' => [
                'label' => 'Nama Dokter',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            // 'senin' => [
            //     'label' => 'Jadwal Senin',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} tidak boleh kosong',
            //     ]
            // ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_poli_id' => $validation->getError('poli_id'),
                'error_dokter_id' => $validation->getError('dokter_id'),
                'error_senin' => $validation->getError('senin'),
                'error_keterangan_senin' => $validation->getError('keterangan_senin'),
                'error_selasa' => $validation->getError('selasa'),
                'error_keterangan_selasa' => $validation->getError('keterangan_selasa'),
                'error_rabu' => $validation->getError('rabu'),
                'error_keterangan_rabu' => $validation->getError('keterangan_rabu'),
                'error_kamis' => $validation->getError('kamis'),
                'error_keterangan_kamis' => $validation->getError('keterangan_kamis'),
                'error_jumat' => $validation->getError('jumat'),
                'error_keterangan_jumat' => $validation->getError('keterangan_jumat'),
                'error_sabtu' => $validation->getError('sabtu'),
                'error_keterangan_sabtu' => $validation->getError('keterangan_sabtu'),
                'error_minggu' => $validation->getError('minggu'),
                'error_keterangan_minggu' => $validation->getError('keterangan_minggu'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $data = [
                'dokter_id' => $dokter_id,
                'poli_id' => $poli_id,
                'senin' => $senin,
                'keterangan_senin' => $keterangan_senin,
                'selasa' => $selasa,
                'keterangan_selasa' => $keterangan_selasa,
                'rabu' => $rabu,
                'keterangan_rabu' => $keterangan_rabu,
                'kamis' => $kamis,
                'keterangan_kamis' => $keterangan_kamis,
                'jumat' => $jumat,
                'keterangan_jumat' => $keterangan_jumat,
                'sabtu' => $sabtu,
                'keterangan_sabtu' => $keterangan_sabtu,
                'minggu' => $minggu,
                'keterangan_minggu' => $keterangan_minggu,
                'last_update' => date('Y-m-d H:i:s'),
            ];

            $this->jadwalpoli->update($idJadwalpoli, $data);

            session()->setFlashdata('success', 'Data Jadwal Poli Berhasil Di Update');
            return redirect()->to('/jadwalpoli');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $iddokter = $this->jadwalpoli->find($id);

            if ($iddokter) {
                $this->jadwalpoli->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }

    public function getDokterSpesialis()
    {
        $spesialisId = $this->request->getVar('spesialis_id');
        $dokter = $this->dokter->where('spesialis_id', $spesialisId)->where('status', 'Y')->findAll();
        $output = '<option value="">Pilih Dokter</option>';
        foreach ($dokter as $row) {
            $output .= '<option value="' . $row['iddokter'] . '">' . $row['nama'] . '</option>';
        }
        return $output;
    }
}
