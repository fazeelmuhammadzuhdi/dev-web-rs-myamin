<?php

namespace App\Controllers\PPID;

use App\Controllers\BaseController;
use App\Models\FormulirPPID;
use Config\Services;
use Hermawan\DataTables\DataTable;

class FormulirPpidController extends BaseController
{

    protected $pesanppid;

    public function __construct()
    {
        $this->pesanppid = new FormulirPPID();
    }

    public function index()
    {
        $data['title'] = 'Formulir Permintaan Informasi PPID';
        return view('backend/formppid/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Formulir Permintaan Informasi PPID';
        return view('backend/formppid/permohonan_informasi_create', $data);
    }


    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->pesanppid->select('idpesanppid,ktp,nama_pemohon_informasi,nomor_telepon_pemohon,email_pemohon,tanggal,informasi_dibutuhkan_pemohon,alasan_permintaan_pemohon,cara_memperoleh_informasi,cara_mengirim_bahan_informasi,keterangan')->orderBy('tanggal', 'desc')->orderBy('idpesanppid', 'DESC');

            return DataTable::of($builder)

                ->edit('tanggal', function ($row) {
                    return date('d M Y', strtotime($row->tanggal)); // Format tanggal sesuai kebutuhan
                })

                ->edit('ktp', function ($row) {
                    if ($row->ktp !== null) {
                        $imageUrl = base_url('ktp/' . $row->ktp);
                        return '
            <img src="' . $imageUrl . '" width="100" height="60" style="cursor:pointer;" 
                data-toggle="modal" data-target="#ktpModal" 
                onclick="showKtpModal(\'' . $imageUrl . '\')">
        ';
                    } else {
                        return '<span class="text-danger">Tidak ada KTP</span>';
                    }
                })



                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $row->idpesanppid . '\',\'' . $row->nama_pemohon_informasi . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>

                      <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $row->idpesanppid . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }

    public function formulirPPIDSave()
    {
        $userId = session()->get('idUser');

        $nama_pemohon_informasi = $this->request->getVar('nama_pemohon_informasi');
        $alamat_pemohon = $this->request->getVar('alamat_pemohon');
        $nomor_telepon_pemohon = $this->request->getVar('nomor_telepon_pemohon');
        $email_pemohon = $this->request->getVar('email_pemohon');
        $informasi_dibutuhkan_pemohon = $this->request->getVar('informasi_dibutuhkan_pemohon');
        $alasan_permintaan_pemohon = $this->request->getVar('alasan_permintaan_pemohon');

        $cara_memperoleh_informasi = $this->request->getVar('cara_memperoleh_informasi');
        $format_bahan_informasi = $this->request->getVar('format_bahan_informasi');
        $cara_mengirim_bahan_informasi = $this->request->getVar('cara_mengirim_bahan_informasi');
        $keterangan = $this->request->getVar('keterangan') ?? '';

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
            'email_pemohon' => [
                'label' => 'Email Pemohon',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'valid_email' => '{field} harus berupa email yang valid',
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
            'alasan_permintaan_pemohon' => [
                'label' => 'Alasan Permintaan Pemohon',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ],
            'cara_memperoleh_informasi' => [
                'label' => 'Cara Memperoleh Informasi',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ],
            'format_bahan_informasi' => [
                'label' => 'Format Bahan Informasi',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ],
            'cara_mengirim_bahan_informasi' => [
                'label' => 'Cara Mengirim Bahan Informasi',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ],
            'ktp' => [
                'label' => 'KTP',
                'rules' => 'uploaded[ktp]|max_size[ktp,2048]|mime_in[ktp,image/jpeg,image/png,image/jpg]',
                'errors' => [
                    'uploaded' => '{field} tidak boleh kosong',
                    'max_size' => 'Ukuran {field} maksimum 2MB',
                    'mime_in' => 'Format {field} harus JPEG, PNG, atau JPG'
                ]
            ],

        ]);

        // // Kirim WhatsApp notifikasi ke admin
        // $whatsappService = Services::whatsapp();

        // // Pesan yang akan dikirim ke admin
        // $pesan = "Formulir Permintaan Informasi PPID dari: $nama_pemohon_informasi\n" .
        //     "Alamat: $alamat_pemohon\n" .
        //     "Nomor Telepon: $nomor_telepon_pemohon\n" .
        //     "Informasi yang Dibutuhkan: $informasi_dibutuhkan_pemohon\n";

        // $pesan = "*📄 Permintaan Informasi PPID*\n\n" .
        //     "*👤 Nama:* $nama_pemohon_informasi\n" .
        //     "*🏠 Alamat:* $alamat_pemohon\n" .
        //     "*📞 Telepon:* $nomor_telepon_pemohon\n" .
        //     "*📌 Informasi yang Dibutuhkan:*\n$informasi_dibutuhkan_pemohon";


        // // Mengirimkan pesan WhatsApp ke admin
        // $whatsappSent = $whatsappService->sendMessageToAdmin($pesan);

        // // Cek apakah pesan berhasil dikirim
        // if (!$whatsappSent) {
        //     session()->setFlashData('error', 'Gagal mengirim pesan WhatsApp!');
        //     return redirect()->back()->withInput();
        // }

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_nama_pemohon_informasi' => $validation->getError('nama_pemohon_informasi'),
                'error_alamat_pemohon' => $validation->getError('alamat_pemohon'),
                'error_nomor_telepon_pemohon' => $validation->getError('nomor_telepon_pemohon'),
                'error_email_pemohon' => $validation->getError('email_pemohon'),
                'error_informasi_dibutuhkan_pemohon' => $validation->getError('informasi_dibutuhkan_pemohon'),
                'error_alasan_permintaan_pemohon' => $validation->getError('alasan_permintaan_pemohon'),
                'error_cara_memperoleh_informasi' => $validation->getError('cara_memperoleh_informasi'),
                'error_format_bahan_informasi' => $validation->getError('format_bahan_informasi'),
                'error_cara_mengirim_bahan_informasi' => $validation->getError('cara_mengirim_bahan_informasi'),
                'error_ktp' => $validation->getError('ktp'),

            ]);
            return redirect()->back()->withInput();
        } else {
            $fileFoto = $this->request->getFile('ktp');

            $namaFoto = "Ktp" . '_' . $fileFoto->getRandomName();
            // Pindahkan file foto ke folder tujuan (public/banner)
            $fileFoto->move(FCPATH . 'ktp', $namaFoto);

            // Menyimpan data ke database
            $this->pesanppid->insert([
                'nama_pemohon_informasi' => $nama_pemohon_informasi,
                'alamat_pemohon' => $alamat_pemohon,
                'nomor_telepon_pemohon' => $nomor_telepon_pemohon,
                'email_pemohon' => $email_pemohon,
                'informasi_dibutuhkan_pemohon' => $informasi_dibutuhkan_pemohon,
                'alasan_permintaan_pemohon' => $alasan_permintaan_pemohon,
                'cara_memperoleh_informasi' => $cara_memperoleh_informasi,
                'format_bahan_informasi' => $format_bahan_informasi,
                'cara_mengirim_bahan_informasi' => $cara_mengirim_bahan_informasi,
                'tanggal' => date('Y-m-d H:i:s'),
                'keterangan' => $keterangan,
                'ktp' => $namaFoto,
            ]);

            if ($userId) {
                session()->setFlashData('success', 'Data berhasil disimpan!');
                return redirect()->to('pesanppid');
            } else {
                session()->setFlashData('success', 'Data berhasil disimpan!');
                return redirect()->back();
            }
        }
    }

    public function edit($id = null)
    {
        $data['pesanppid'] = $this->pesanppid->find($id);
        $data['title'] = 'Form Edit Formulir Permintaan Informasi PPID';
        return view('backend/formppid/permohonan_informasi_edit', $data);
    }

    public function update()
    {

        $idpesanppid = $this->request->getVar('idpesanppid');
        $nama_pemohon_informasi = $this->request->getVar('nama_pemohon_informasi');
        $alamat_pemohon = $this->request->getVar('alamat_pemohon');
        $nomor_telepon_pemohon = $this->request->getVar('nomor_telepon_pemohon');
        $email_pemohon = $this->request->getVar('email_pemohon');
        $informasi_dibutuhkan_pemohon = $this->request->getVar('informasi_dibutuhkan_pemohon');
        $alasan_permintaan_pemohon = $this->request->getVar('alasan_permintaan_pemohon');

        $cara_memperoleh_informasi = $this->request->getVar('cara_memperoleh_informasi');
        $format_bahan_informasi = $this->request->getVar('format_bahan_informasi');
        $cara_mengirim_bahan_informasi = $this->request->getVar('cara_mengirim_bahan_informasi');
        $keterangan = $this->request->getVar('keterangan') ?? '';

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
            'email_pemohon' => [
                'label' => 'Email Pemohon',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'valid_email' => '{field} harus berupa email yang valid',
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
            'alasan_permintaan_pemohon' => [
                'label' => 'Alasan Permintaan Pemohon',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ],
            'cara_memperoleh_informasi' => [
                'label' => 'Cara Memperoleh Informasi',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ],
            'format_bahan_informasi' => [
                'label' => 'Format Bahan Informasi',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ],
            'cara_mengirim_bahan_informasi' => [
                'label' => 'Cara Mengirim Bahan Informasi',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'max_length' => '{field} maksimal 255 karakter',
                ]
            ]
        ]);


        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_nama_pemohon_informasi' => $validation->getError('nama_pemohon_informasi'),
                'error_alamat_pemohon' => $validation->getError('alamat_pemohon'),
                'error_nomor_telepon_pemohon' => $validation->getError('nomor_telepon_pemohon'),
                'error_email_pemohon' => $validation->getError('email_pemohon'),
                'error_informasi_dibutuhkan_pemohon' => $validation->getError('informasi_dibutuhkan_pemohon'),
                'error_alasan_permintaan_pemohon' => $validation->getError('alasan_permintaan_pemohon'),
                'error_cara_memperoleh_informasi' => $validation->getError('cara_memperoleh_informasi'),
                'error_format_bahan_informasi' => $validation->getError('format_bahan_informasi'),
                'error_cara_mengirim_bahan_informasi' => $validation->getError('cara_mengirim_bahan_informasi')

            ]);
            return redirect()->back()->withInput();
        } else {
            $data = [
                'nama_pemohon_informasi' => $nama_pemohon_informasi,
                'alamat_pemohon' => $alamat_pemohon,
                'nomor_telepon_pemohon' => $nomor_telepon_pemohon,
                'email_pemohon' => $email_pemohon,
                'informasi_dibutuhkan_pemohon' => $informasi_dibutuhkan_pemohon,
                'alasan_permintaan_pemohon' => $alasan_permintaan_pemohon,
                'cara_memperoleh_informasi' => $cara_memperoleh_informasi,
                'format_bahan_informasi' => $format_bahan_informasi,
                'cara_mengirim_bahan_informasi' => $cara_mengirim_bahan_informasi,
                'tanggal' => date('Y-m-d H:i:s'),
                'keterangan' => $keterangan,
            ];

            $this->pesanppid->update($idpesanppid, $data);

            session()->setFlashdata('success', 'Data Permohonan Informasi Berhasil Di Update');
            return redirect()->to('/pesanppid');
        }
    }

    public function delete($id = null)
    {

        if ($this->request->isAJAX()) {
            $keterangan = $this->pesanppid->find($id);

            if ($keterangan) {
                if ($keterangan['ktp'] !== null) {
                    $fotoPath = FCPATH . 'ktp/' . $keterangan['ktp'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }

                $this->pesanppid->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }

    public function cetakLaporanPermintaanInformasiPPID()
    {
        $data['permintaaninformasi'] = $this->pesanppid->findAll();
        $data['title'] = 'Laporan Permintaan Informasi PPID';

        $data['total'] = count($data['permintaaninformasi']);

        // Konversi gambar menjadi Base64
        $logoPemrov = base64_encode(file_get_contents(FCPATH . 'assets/pemprov.jpg'));
        $data['srcLogoPemrov'] = 'data:image/png;base64,' . $logoPemrov;

        $logoRsud = base64_encode(file_get_contents(FCPATH . 'assets/logo.png'));
        $data['srcLogoRsud'] = 'data:image/png;base64,' . $logoRsud;


        return view('backend/formppid/laporan_permintaan_informasi', $data);
    }
}
