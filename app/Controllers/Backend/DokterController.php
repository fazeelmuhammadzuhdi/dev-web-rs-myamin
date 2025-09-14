<?php

namespace App\Controllers\Backend;

use App\Models\Dokter;
use App\Models\Spesialis;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class DokterController extends BaseController
{
    protected $dokter;
    protected $spesialis;

    public function __construct()
    {
        $this->dokter = new Dokter();
        $this->spesialis = new Spesialis();
    }

    public function index()
    {
        $data['title'] = 'Dokter';

        return view('backend/dokter/index', $data);
    }

    public function create()
    {
        // get data spesalis dimana status aktif atau = Y
        $data['spesialis'] = $this->spesialis->where('status', 'Y')->orderBy('nama', 'asc')->findAll();

        return view('backend/dokter/create', $data);
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {

            $builder = $this->dokter->getDokter();

            return DataTable::of($builder)
                ->edit('nip', function ($row) {
                    return '<span class="badge badge-primary text-white">' . $row->nip . '</span>';
                })
                ->edit('status', function ($row) {
                    if ($row->status == 'Y') {
                        return '<span class="badge badge-success">Aktif</span>';
                    } else {
                        return '<span class="badge badge-warning">Tidak Aktif</span>';
                    }
                })
                ->edit('gambar', function ($row) {
                    if ($row->gambar !== null) {
                        $imageUrl = base_url('dokter/' . $row->gambar);
                        return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" width="110" height="90"></a>';
                    } else {
                        return '';
                    }
                })
                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->iddokter . '\',\'' . $row->nama_dokter . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->iddokter . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $idspesialis = $this->request->getVar('spesialis_id');
        $nip = $this->request->getVar('nip');
        $nama = $this->request->getVar('nama');
        $keterangan = $this->request->getVar('keterangan');
        $status = $this->request->getVar('status');

        $rules = $this->validate([
            'spesialis_id' => [
                'label' => 'Nama Spesialis',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'nip' => [
                'label' => 'NIP Dokter',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'nama' => [
                'label' => 'Tempat Tidur VIP Kosong',
                'rules' => 'required',
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
            'gambar' => [
                'label' => 'Gambar Dokter',
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
                'error_spesialis_id' => $validation->getError('spesialis_id'),
                'error_nip' => $validation->getError('nip'),
                'error_nama' => $validation->getError('nama'),
                'error_keterangan' => $validation->getError('keterangan'),
                'error_gambar' => $validation->getError('gambar'),

            ]);
            return redirect()->back()->withInput();
        } else {
            $fileFoto = $this->request->getFile('gambar');

            $namaFoto = "Dokter" . '_' . $fileFoto->getRandomName();
            // Pindahkan file foto ke folder tujuan (public/dokter)
            $fileFoto->move(FCPATH . 'dokter', $namaFoto);

            $this->dokter->insert([
                'spesialis_id' => $idspesialis,
                'nip' => $nip,
                'nama' => $nama,
                'keterangan' => $keterangan,
                'gambar' => $namaFoto,
                'thumbnail' => $namaFoto,
                'status' => $status ? $status : 'Y',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata('success', 'Data Dokter Berhasil Di Tambahkan');
            return redirect()->to('/dokters');
        }
    }

    public function edit($id = null)
    {
        $data['spesialis'] = $this->spesialis->where('status', 'Y')->orderBy('nama', 'asc')->findAll();

        $data['dokters'] = $this->dokter->find($id);
        return view('backend/dokter/edit', $data);
    }

    public function update()
    {

        $idDokter = $this->request->getVar('iddokter');
        $idspesialis = $this->request->getVar('spesialis_id');
        $nip = $this->request->getVar('nip');
        $nama = $this->request->getVar('nama');
        $keterangan = $this->request->getVar('keterangan');
        $status = $this->request->getVar('status');
        $gambar = $this->request->getFile('gambar');


        $rules = [
            'spesialis_id' => [
                'label' => 'Nama Spesialis',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],


            'nama' => [
                'label' => 'Nama Dokter',
                'rules' => 'required',
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
                'error_spesialis_id' => $validation->getError('spesialis_id'),
                'error_nip' => $validation->getError('nip'),
                'error_nama' => $validation->getError('nama'),
                'error_keterangan' => $validation->getError('keterangan'),
            ]);

            return redirect()->back()->withInput();
        } else {

            // Menghapus foto lama jika ada foto baru diunggah
            if ($gambar->isValid() && !$gambar->hasMoved()) {
                $dokter = $this->dokter->find($idDokter);
                if ($dokter['gambar'] !== null) {
                    $oldFotoPath = FCPATH . 'dokter/' . $dokter['gambar'];
                    if (file_exists($oldFotoPath)) {
                        unlink($oldFotoPath);
                    }
                }

                $newFotoName = "Dokter" . '_' . $gambar->getRandomName();
                $gambar->move(FCPATH . 'dokter', $newFotoName);

                // Update data dokter dengan foto baru
                $this->dokter->update($idDokter, [
                    'nip' => $nip,
                    'nama' => $nama,
                    'status' => $status ? $status : 'Y',
                    'keterangan' => $keterangan,
                    'spesialis_id' => $idspesialis,
                    'gambar' => $newFotoName,
                    'thumbnail' => $newFotoName,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                // Jika tidak ada foto baru diunggah, update data dokter tanpa foto
                $this->dokter->update($idDokter, [
                    'nip' => $nip,
                    'nama' => $nama,
                    'status' => $status ? $status : 'Y',
                    'keterangan' => $keterangan,
                    'spesialis_id' => $idspesialis,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            session()->setFlashdata('success', 'Data Dokter Berhasil Di Update');
            return redirect()->to('/dokters');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $cekReferensi = $this->dokter->find($id);

            if ($cekReferensi) {
                // Menghapus foto jika ada
                if ($cekReferensi['gambar'] !== null) {
                    $fotoPath = FCPATH . 'dokter/' . $cekReferensi['gambar'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }

                // Menghapus data dari database
                $this->dokter->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
