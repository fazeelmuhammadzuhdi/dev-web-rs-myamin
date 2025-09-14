<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Pengaduan;
use Config\Services;
use Hermawan\DataTables\DataTable;

class PengaduanController extends BaseController
{
    protected $pengaduan;

    public function __construct()
    {
        $this->pengaduan = new Pengaduan();
    }

    public function index()
    {
        $data['title'] = 'Pengaduan Masyarakat PPT Bunga Seroja';
        return view('backend/pengaduan/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Formulir Pengaduan Masyarakat PPT Bunga Seroja';
        return view('frontend/pengaduan-bunga-seroja', $data);
    }


    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->pengaduan->select('idpengaduan,nama,nomor_hp,alamat,kronologi,created_at')->orderBy('created_at', 'desc')->orderBy('idpengaduan', 'DESC');

            return DataTable::of($builder)

                ->edit('created_at', function ($row) {
                    return tanggal_indonesia($row->created_at);
                })

                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $row->idpengaduan . '\',\'' . $row->nama . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>

                      
                    </div>';
                }, 'last')
                ->toJson();
        }
    }

    public function save()
    {
        $nama = $this->request->getVar('nama');
        $alamat = $this->request->getVar('alamat');
        $nomor_hp = $this->request->getVar('nomor_hp');
        $kronologi = $this->request->getVar('kronologi');

        // Validasi input
        $rules = $this->validate([
            'nama' => [
                'label' => 'Nama',
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'alpha_space' => '{field} hanya boleh mengandung huruf dan spasi',
                ]
            ],

            'alamat' => [
                'label' => 'Alamat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    // 'alpha_space' => '{field} hanya boleh mengandung huruf dan spasi',
                ]
            ],
            'nomor_hp' => [
                'label' => 'Nomor Handphone',
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'numeric' => '{field} harus berupa angka',
                    'min_length' => '{field} minimal 10 digit',
                    'max_length' => '{field} maksimal 15 digit',
                ]
            ],

            'kronologi' => [
                'label' => 'Kronologi Kejadian',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        // Kirim WhatsApp notifikasi ke admin
        $whatsappService = Services::whatsapp();

        $pesan = "🚨 *LAPORAN MASYARAKAT PPT BUNGA SEROJA *\n" .
            "────────────────────────────\n" .
            "👤 *Nama:* {$nama}\n" .
            "🏠 *Alamat:* {$alamat}\n" .
            "📞 *Nomor HP:* {$nomor_hp}\n" .
            "────────────────────────────\n" .
            "📜 *Kronologi Kejadian:*\n{$kronologi}\n" .
            "────────────────────────────";


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
                'error_nama' => $validation->getError('nama'),
                'error_alamat' => $validation->getError('alamat'),
                'error_nomor_hp' => $validation->getError('nomor_hp'),
                'error_kronologi' => $validation->getError('kronologi'),

            ]);
            return redirect()->back()->withInput();
        } else {
            // Menyimpan data ke database
            $this->pengaduan->insert([
                'nama' => $nama,
                'alamat' => $alamat,
                'nomor_hp' => $nomor_hp,
                'kronologi' => $kronologi,
                'created_at' => date('Y-m-d H:i:s'),
            ]);


            session()->setFlashData('success', 'Data Berhasil Terkirim!');

            return redirect()->back();
        }
    }


    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $keterangan = $this->pengaduan->find($id);

            if ($keterangan) {
                $this->pengaduan->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
