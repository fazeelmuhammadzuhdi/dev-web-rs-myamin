<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\Poli;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class PoliController extends BaseController
{
    protected $poli;

    public function __construct()
    {
        $this->poli = new Poli();
        helper('slug');
    }

    public function index()
    {
        $data['title'] = 'Poli';

        return view('backend/poli/index', $data);
    }

    public function create()
    {
        return view('backend/poli/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->poli->select('idpoli,nama,keterangan,status,gambar')->orderBy('nama', 'ASC');
            return DataTable::of($builder)
                ->edit('status', function ($row) {
                    if ($row->status == 'Y') {
                        return '<span class="badge badge-success">Aktif</span>';
                    } else {
                        return '<span class="badge badge-warning">Tidak Aktif</span>';
                    }
                })
                ->edit('gambar', function ($row) {
                    if ($row->gambar !== null) {
                        return '<img src="' . base_url('polikliniks/' . $row->gambar) . '" width="50" height="50">';
                    } else {
                        return '';
                    }
                })
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

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idpoli . '\',\'' . $row->nama . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idpoli . '\')">
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
                'label' => 'Nama Poli',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'keterangan' => [
                'label' => 'Keterangan Poli',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'gambar' => [
                'label' => 'Gambar Poli',
                'rules' => 'uploaded[gambar]|max_size[gambar,1024]|mime_in[gambar,image/jpeg,image/png,image/jpg]',
                'errors' => [
                    'uploaded' => '{field} tidak boleh kosong',
                    'max_size' => 'Ukuran {field} maksimum 1MB',
                    'mime_in' => 'Format {field} harus JPEG ,PNG atau JPG'
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
            $fileFoto = $this->request->getFile('gambar');

            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'polikliniks', $namaFoto);

            $this->poli->insert([
                'nama' => $nama,
                'keterangan' => $keterangan,
                'status' => $status ? $status : 'Y',
                'created_at' => date('Y-m-d H:i:s'),
                'slug' => createSlug($nama),
                'gambar' => $namaFoto,
            ]);

            session()->setFlashdata('success', 'Data Poli Berhasil Di Tambahkan');
            return redirect()->to('/poly');
        }
    }

    public function edit($id = null)
    {
        $data['poli'] = $this->poli->find($id);
        return view('backend/poli/edit', $data);
    }

    public function update()
    {

        $idPoli = $this->request->getVar('idpoli');
        $nama = $this->request->getVar('nama');
        $status = $this->request->getVar('status');
        $gambar = $this->request->getFile('gambar');
        $keterangan = $this->request->getVar('keterangan');

        $rules = [

            'nama' => [
                'label' => 'Nama Poli',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'keterangan' => [
                'label' => 'Keterangan Poli',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ];

        if ($gambar->isValid() && !$gambar->hasMoved()) {
            // Validasi gambar
            $rules['gambar'] = 'uploaded[gambar]|mime_in[gambar,image/jpeg,image/png]|max_size[gambar,1024]';
        }

        $validation = \Config\Services::validation();
        $isValid = $validation->withRequest($this->request)->setRules($rules)->run();

        if (!$isValid) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_nama' => $validation->getError('nama'),
                'error_keterangan' => $validation->getError('keterangan'),
            ]);
            return redirect()->back()->withInput();
        } else {



            // Menghapus foto lama jika ada foto baru diunggah
            if ($gambar->isValid() && !$gambar->hasMoved()) {
                $poli = $this->poli->find($idPoli);
                if ($poli['gambar'] !== null) {
                    $oldFotoPath = FCPATH . 'polikliniks/' . $poli['gambar'];
                    if (file_exists($oldFotoPath)) {
                        unlink($oldFotoPath);
                    }
                }

                $newFotoName = $gambar->getRandomName();
                $gambar->move(FCPATH . 'polikliniks', $newFotoName);

                // Update data poli dengan foto baru
                $this->poli->update($idPoli, [
                    'nama' => $nama,
                    'keterangan' => $keterangan,
                    'status' => $status ? $status : 'Y',
                    'slug' => createSlug($nama),

                    'gambar' => $newFotoName,
                ]);
            } else {
                // Jika tidak ada foto baru diunggah, update data poli tanpa foto
                $this->poli->update($idPoli, [
                    'nama' => $nama,
                    'keterangan' => $keterangan,
                    'slug' => createSlug($nama),

                    'status' => $status ? $status : 'Y',
                ]);
            }

            session()->setFlashdata('success', 'Data Poli Berhasil Di Update');
            return redirect()->to('/poly');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $cekReferensi = $this->poli->find($id);

            if ($cekReferensi) {
                // Menghapus foto jika ada
                if ($cekReferensi['gambar'] !== null) {
                    $fotoPath = FCPATH . 'polikliniks/' . $cekReferensi['gambar'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }

                // Menghapus data dari database
                $this->poli->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
