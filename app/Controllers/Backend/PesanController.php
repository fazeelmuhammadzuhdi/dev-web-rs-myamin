<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\Pesan;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class PesanController extends BaseController
{
    protected $pesan;

    public function __construct()
    {
        $this->pesan = new Pesan();
    }

    public function index()
    {
        $data['title'] = 'Pesan';

        return view('backend/pesan/index', $data);
    }


    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->pesan->select('idpesan,tanggal,nama,pesan,status,status_baca,respon')->orderBy('tanggal', 'DESC');
            return DataTable::of($builder)
                ->edit('status', function ($row) {
                    if ($row->status == 'UP') {
                        return '<span class="badge badge-danger">Unpbulish</span>';
                    } else {
                        return '<span class="badge badge-success">Publish</span>';
                    }
                })
                ->edit('status_baca', function ($row) {
                    if ($row->status == 'UR') {
                        return '<span class="badge badge-danger">Belum Dibaca</span>';
                    } else {
                        return '<span class="badge badge-success">Dibaca</span>';
                    }
                })
                ->edit('tanggal', function ($row) {
                    return date('d M Y', strtotime($row->tanggal)); // Format tanggal sesuai kebutuhan
                })
                ->edit('respon', function ($row) {
                    if ($row->respon) {
                        $doc = new DOMDocument();
                        @$doc->loadHTML($row->respon);
                        $text =  $doc->textContent;

                        $text =  limit_words($text, 10);


                        return $text;
                    }
                    return '-';
                })
                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idpesan . '\',\'' . $row->nama . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idpesan . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }

    public function edit($id = null)
    {
        if ($id !== null) {
            // Update status baca pesan
            $this->pesan->updateStatusBaca($id);

            // Ambil data pesan dan tampilkan form edit
            $data['pesan'] = $this->pesan->find($id);
            return view('backend/pesan/edit', $data);
        } else {
            session()->setFlashdata('error', 'Data Tidak Ditemukan');
            return redirect()->back();
        }
    }

    public function save()
    {
        $nama = $this->request->getVar('nama');
        $email = $this->request->getVar('email');
        $judul = $this->request->getVar('judul');
        $pesan = $this->request->getVar('pesan');


        // debug

        // dd($nama, $email, $judul, $pesan);

        $rules = $this->validate([
            'nama' => [
                'label' => 'Nama',
                'rules' => 'required|alpha_space|max_length[50]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'alpha_space' => '{field} hanya boleh mengandung huruf, angka dan spasi',
                ]
            ],

            'email' => [
                'label' => 'Email',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'judul' => [
                'label' => 'Judul',
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'alpha_space' => '{field} hanya boleh mengandung huruf, angka dan spasi',
                ]
            ],
            'pesan' => [
                'label' => 'Pesan',
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'alpha_space' => '{field} hanya boleh mengandung huruf, angka dan spasi',

                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_nama' => $validation->getError('nama'),
                'error_email' => $validation->getError('email'),
                'error_pesan' => $validation->getError('pesan'),
                'error_judul' => $validation->getError('judul'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->pesan->insert([
                'nama' => $nama,
                'email' => $email,
                'judul' => $judul,
                'pesan' => $pesan,
                // date now
                'tanggal' => date('Y-m-d'), // date now 
            ]);

            session()->setFlashdata('success', 'Pesan Berhasil Terkirim');
            return redirect()->back();
        }
    }

    public function update()
    {

        $idPesan = $this->request->getVar('idpesan');
        $respon = $this->request->getVar('respon');
        $status = $this->request->getVar('status');

        $rules = $this->validate([

            'respon' => [
                'label' => 'Nama Pesan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_respon' => $validation->getError('respon'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $data = [
                'respon' => $respon,
                'status' => $status ? $status : 'UP',
                'admin' => 'Admin',
            ];

            $this->pesan->update($idPesan, $data);

            session()->setFlashdata('success', 'Pesan Berhasil Di Jawab');
            return redirect()->to('/pesans');
        }
    }


    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $keterangan = $this->pesan->find($id);

            if ($keterangan) {
                $this->pesan->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
