<?php

namespace App\Controllers\PPID;

use App\Controllers\BaseController;
use App\Models\KeberatanInformasiPPID;
use Config\Services;
use Hermawan\DataTables\DataTable;

class KeberatanInformasiController extends BaseController
{
    protected $keberataninformasippid;

    public function __construct()
    {
        $this->keberataninformasippid = new KeberatanInformasiPPID();
    }

    public function index()
    {
        $data['title'] = 'Formulir Keberatan Atas Permohonan Informasi PPID';
        return view('backend/formppid/keberatan_informasi', $data);
    }

    public function create()
    {
        return view('backend/formppid/keberatan_informasi_create');
    }



    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->keberataninformasippid->select('idkeberataninformasippid,nama_pemohon_informasi,pekerjaan,nomor_telepon_pemohon,tanggal,informasi_dibutuhkan_pemohon,alasan_pengajuan,keterangan')->orderBy('tanggal', 'desc')->orderBy('idkeberataninformasippid', 'DESC');

            return DataTable::of($builder)

                ->edit('tanggal', function ($row) {
                    return date('d M Y', strtotime($row->tanggal)); // Format tanggal sesuai kebutuhan
                })

                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $row->idkeberataninformasippid . '\',\'' . $row->nama_pemohon_informasi . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>

                    <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $row->idkeberataninformasippid . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }

    public function formulirKeberatanInformasiPPIDSave()
    {
        $userId = session()->get('idUser');

        $nama_pemohon_informasi = $this->request->getVar('nama_pemohon_informasi');
        $alamat_pemohon = $this->request->getVar('alamat_pemohon');
        $nomor_telepon_pemohon = $this->request->getVar('nomor_telepon_pemohon');
        $informasi_dibutuhkan_pemohon = $this->request->getVar('informasi_dibutuhkan_pemohon');
        $alasan_pengajuan = $this->request->getVar('alasan_pengajuan');
        $pekerjaan = $this->request->getVar('pekerjaan');
        $keterangan = $this->request->getVar('keterangan') ?? null;

        // Validasi input
        $rules = $this->validate([
            'nama_pemohon_informasi' => [
                'label' => 'Nama Pemohon Informasi',
                'rules' => 'required|alpha_space|max_length[50]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'alpha_space' => '{field} hanya boleh mengandung huruf dan spasi',
                ]
            ],
            'alamat_pemohon' => [
                'label' => 'Alamat Pemohon',
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'pekerjaan' => [
                'label' => 'Pekerjaan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'nomor_telepon_pemohon' => [
                'label' => 'Nomor Telepon Pemohon',
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} harus berupa angka',
                    'min_length' => '{field} minimal 10 digit',
                    'max_length' => '{field} maksimal 15 digit',
                ]
            ],
            'informasi_dibutuhkan_pemohon' => [
                'label' => 'Informasi Dibutuhkan Pemohon',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ],
            'alasan_pengajuan' => [
                'label' => 'Alasan Permintaan Pemohon',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ],

        ]);

        // Kirim WhatsApp notifikasi ke admin
        $whatsappService = Services::whatsapp();

        // Pesan yang akan dikirim ke admin
        $pesan = "Formulir Keberatan Atas Permohonan Informasi PPID dari: $nama_pemohon_informasi\n" .
            "Alamat: $alamat_pemohon\n" .
            "Nomor Telepon: $nomor_telepon_pemohon\n" .
            "Tujuan Penggunaan Informasi: $informasi_dibutuhkan_pemohon\n";
        "Alasan Pengajuan: $alasan_pengajuan\n";

        // Mengirimkan pesan WhatsApp ke admin
        $whatsappSent = $whatsappService->sendMessageToAdmin($pesan);

        // Cek apakah pesan berhasil dikirim
        if (!$whatsappSent) {
            session()->setFlashData('error', 'Gagal mengirim pesan WhatsApp!');
            return redirect()->back()->withInput();
        }

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_nama_pemohon_informasi' => $validation->getError('nama_pemohon_informasi'),
                'error_alamat_pemohon' => $validation->getError('alamat_pemohon'),
                'error_nomor_telepon_pemohon' => $validation->getError('nomor_telepon_pemohon'),
                'error_informasi_dibutuhkan_pemohon' => $validation->getError('informasi_dibutuhkan_pemohon'),
                'error_alasan_pengajuan' => $validation->getError('alasan_pengajuan'),
                'error_pekerjaan' => $validation->getError('pekerjaan'),

            ]);
            return redirect()->back()->withInput();
        } else {
            // Menyimpan data ke database
            $this->keberataninformasippid->insert([
                'nama_pemohon_informasi' => $nama_pemohon_informasi,
                'alamat_pemohon' => $alamat_pemohon,
                'nomor_telepon_pemohon' => $nomor_telepon_pemohon,
                'informasi_dibutuhkan_pemohon' => $informasi_dibutuhkan_pemohon,
                'alasan_pengajuan' => $alasan_pengajuan,
                'pekerjaan' => $pekerjaan,
                'tanggal' => date('Y-m-d H:i:s'),
                'keterangan' => $keterangan
            ]);

            if ($userId) {
                session()->setFlashData('success', 'Data berhasil disimpan!');
                return redirect()->to('/keberataninformasi');
            } else {
                session()->setFlashData('success', 'Data berhasil disimpan!');
                return redirect()->back();
            }
        }
    }

    public function edit($id = null)
    {
        $data['keberataninformasippid'] = $this->keberataninformasippid->find($id);
        return view('backend/formppid/keberatan_informasi_edit', $data);
    }

    public function update()
    {

        $idkeberataninformasippid = $this->request->getVar('idkeberataninformasippid');
        $nama_pemohon_informasi = $this->request->getVar('nama_pemohon_informasi');
        $alamat_pemohon = $this->request->getVar('alamat_pemohon');
        $nomor_telepon_pemohon = $this->request->getVar('nomor_telepon_pemohon');
        $informasi_dibutuhkan_pemohon = $this->request->getVar('informasi_dibutuhkan_pemohon');
        $alasan_pengajuan = $this->request->getVar('alasan_pengajuan');
        $pekerjaan = $this->request->getVar('pekerjaan');
        $keterangan = $this->request->getVar('keterangan');

        $rules = $this->validate([
            'nama_pemohon_informasi' => [
                'label' => 'Nama Pemohon Informasi',
                'rules' => 'required|alpha_space|max_length[50]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'alpha_space' => '{field} hanya boleh mengandung huruf dan spasi',
                ]
            ],
            'alamat_pemohon' => [
                'label' => 'Alamat Pemohon',
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'nomor_telepon_pemohon' => [
                'label' => 'Nomor Telepon Pemohon',
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} harus berupa angka',
                    'min_length' => '{field} minimal 10 digit',
                    'max_length' => '{field} maksimal 15 digit',
                ]
            ],
            'informasi_dibutuhkan_pemohon' => [
                'label' => 'Informasi Dibutuhkan Pemohon',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ],
            'alasan_pengajuan' => [
                'label' => 'Alasan Permintaan Pemohon',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
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
                'nama_pemohon_informasi' => $nama_pemohon_informasi,
                'alamat_pemohon' => $alamat_pemohon,
                'nomor_telepon_pemohon' => $nomor_telepon_pemohon,
                'informasi_dibutuhkan_pemohon' => $informasi_dibutuhkan_pemohon,
                'alasan_pengajuan' => $alasan_pengajuan,
                'pekerjaan' => $pekerjaan,
                'keterangan' => $keterangan
            ];

            $this->keberataninformasippid->update($idkeberataninformasippid, $data);

            session()->setFlashdata('success', 'Data Keberatan Informasi Berhasil Di Update');
            return redirect()->to('/keberataninformasi');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $keterangan = $this->keberataninformasippid->find($id);

            if ($keterangan) {
                $this->keberataninformasippid->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }

    public function cetakLaporanKeberatanInformasiPPID()
    {
        $data['keberataninformasi'] = $this->keberataninformasippid->findAll();
        $data['title'] = 'Laporan Keberatan Informasi PPID';

        $data['total'] = count($data['keberataninformasi']);

        // Konversi gambar menjadi Base64
        $logoPemrov = base64_encode(file_get_contents(FCPATH . 'assets/pemprov.jpg'));
        $data['srcLogoPemrov'] = 'data:image/png;base64,' . $logoPemrov;

        $logoRsud = base64_encode(file_get_contents(FCPATH . 'assets/logo.png'));
        $data['srcLogoRsud'] = 'data:image/png;base64,' . $logoRsud;


        return view('backend/formppid/laporan_keberatan_informasi', $data);
    }
}
