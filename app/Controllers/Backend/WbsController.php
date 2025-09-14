<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Wbs;
use Hermawan\DataTables\DataTable;

class WbsController extends BaseController
{
    protected $laporwbs;

    public function __construct()
    {
        $this->laporwbs = new Wbs();
    }

    public function index()
    {
        $data['title'] = 'Informasi Data Laporan Whistleblowing System';
        return view('backend/laporwbs/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Formulir Pelaporan Whistleblowing System';
        $data['tindakans'] = [
            'Fraud',
            'Gratifikasi dan penyuapan',
            'Konflik kepentingan',
            'Pemerasan',
            'Penyalahgunaan aset perusahaan',
            'Penyalahgunaan wewenang',
            'Perilaku yang tidak sesuai dengan etika bisnis dan kerja',
            'Pelanggaran Peraturan Perundang-undangan yang berpotensi mencemarkan nama baik',
            'Pelanggaran Kode Etik',
            'Perbuatan melanggar hukum',
            'Transaksi Mencurigakan',
            'Lain - lain'

        ];
        return view('frontend/whistleblowing', $data);
    }


    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->laporwbs->select('id_wbs,nama_pelapor,telepon_pelapor,tindakan,nama_terlapor,waktu_kejadian,lokasi_kejadian,kronologis')->orderBy('waktu_kejadian', 'desc');

            return DataTable::of($builder)

                ->edit('waktu_kejadian', function ($row) {
                    return '<span class="text-nowrap">' . tanggal_indonesia($row->waktu_kejadian) . '</span>';
                })

                ->edit('tindakan', function ($row) {
                    $clean = html_entity_decode($row->tindakan); // ubah &quot; jadi "
                    $tindakans = json_decode($clean, true); // decode string JSON jadi array

                    if (is_array($tindakans)) {
                        $badges = '';
                        foreach ($tindakans as $item) {
                            $badges .= '<span class="badge badge-primary m-1">' . esc($item) . '</span>';
                        }
                        return $badges;
                    } else {
                        return '<span class="text-danger">Format tidak valid</span>';
                    }
                })

                ->edit('nama_pelapor', function ($row) {
                    return '<span class="text-nowrap">' . esc($row->nama_pelapor) . '</span>';
                })

                // ->add('action', function ($row) {
                //     return  '<div class="d-flex " role="group">

                //     <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $row->id_wbs . '\',\'' . $row->nama_pelapor . '\')">
                //       <i class="feather icon-trash-2"></i>
                //     </button>

                //     </div>';
                // }, 'last')
                ->toJson();
        }
    }

    public function save()
    {
        // Validasi input
        $rules = $this->validate([
            'nama_pelapor' => [
                'label' => 'Nama Pelapor',
                'rules' => 'required|alpha_space|max_length[100]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'alpha_space' => '{field} hanya boleh mengandung huruf dan spasi',
                ]
            ],

            'nama_terlapor' => [
                'label' => 'Nama Terlapor',
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'telepon_pelapor' => [
                'label' => 'Nomor Telepon Pelapor',
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} harus berupa angka',
                    'min_length' => '{field} minimal 10 digit',
                    'max_length' => '{field} maksimal 15 digit',
                ]
            ],
            'email_pelapor' => [
                'label' => 'Email Pelapor',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'valid_email' => '{field} harus berupa email yang valid',
                ]
            ],
            'lokasi_kejadian' => [
                'label' => 'Lokasi Kejadian',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ],
            'kronologis' => [
                'label' => 'Kronologis Kejadian',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'tindakan' => [
                'label' => 'Tindakan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'waktu_kejadian' => [
                'label' => 'Waktu Kejadian',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);


        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_nama_pelapor' => $validation->getError('nama_pelapor'),
                'error_nama_terlapor' => $validation->getError('nama_terlapor'),
                'error_telepon_pelapor' => $validation->getError('telepon_pelapor'),
                'error_email_pelapor' => $validation->getError('email_pelapor'),
                'error_lokasi_kejadian' => $validation->getError('lokasi_kejadian'),
                'error_kronologis' => $validation->getError('kronologis'),
                'error_tindakan' => $validation->getError('tindakan'),
                'error_waktu_kejadian' => $validation->getError('waktu_kejadian'),

            ]);
            return redirect()->back()->withInput();
        } else {

            // Ambil semua data setelah validasi berhasil
            $nama_pelapor = $this->request->getVar('nama_pelapor');
            $nama_terlapor = $this->request->getVar('nama_terlapor');
            $telepon_pelapor = $this->request->getVar('telepon_pelapor');
            $email_pelapor = $this->request->getVar('email_pelapor');
            $lokasi_kejadian = $this->request->getVar('lokasi_kejadian');
            $waktu_kejadian = $this->request->getVar('waktu_kejadian');
            $kronologis = $this->request->getVar('kronologis');
            $tindakan = $this->request->getVar('tindakan');

            // Pastikan tindakan array sebelum encode
            if (!is_array($tindakan)) {
                $tindakan = [$tindakan];
            }

            // Menyimpan data ke database
            $this->laporwbs->insert([
                'nama_pelapor' => $nama_pelapor,
                'nama_terlapor' => $nama_terlapor,
                'telepon_pelapor' => $telepon_pelapor,
                'email_pelapor' => $email_pelapor,
                'lokasi_kejadian' => $lokasi_kejadian,
                'kronologis' => $kronologis,
                'tindakan' => json_encode($tindakan),
                'waktu_kejadian' => $waktu_kejadian,
            ]);

            session()->setFlashData('success', 'Data berhasil terkirim!');
            return redirect()->back();
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $keterangan = $this->laporwbs->find($id);

            if ($keterangan) {
                $this->laporwbs->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }

    public function cetakLaporanWbs()
    {
        $data['whistleblowing'] = $this->laporwbs->findAll();


        $data['total'] = count($data['whistleblowing']);

        // Konversi gambar menjadi Base64
        $logoPemrov = base64_encode(file_get_contents(FCPATH . 'assets/pemprov.jpg'));
        $data['srcLogoPemrov'] = 'data:image/png;base64,' . $logoPemrov;

        $logoRsud = base64_encode(file_get_contents(FCPATH . 'assets/logo.png'));
        $data['srcLogoRsud'] = 'data:image/png;base64,' . $logoRsud;


        return view('backend/laporwbs/laporan-wbs', $data);
    }
}
