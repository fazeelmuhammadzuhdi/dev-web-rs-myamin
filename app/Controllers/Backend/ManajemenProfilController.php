<?php

namespace App\Controllers\Backend;

use App\Models\Manajemen;
use App\Models\ManajemenProfil;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class ManajemenProfilController extends BaseController
{
    protected $manajemenprofil;
    protected $manajemen;

    public function __construct()
    {
        $this->manajemenprofil = new ManajemenProfil();
        $this->manajemen = new Manajemen();
    }

    public function index()
    {
        $data['title'] = 'Manajemen Profil';

        return view('backend/manajemenprofil/index', $data);
    }

    public function create()
    {
        $data['manajemen'] = $this->manajemen->orderBy('idmanajemen', 'asc')->where('status', 'Y')->findAll();
        // $data['manajemen'] = $this->manajemen->findAll();

        return view('backend/manajemenprofil/create', $data);
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {

            $builder = $this->manajemenprofil->getManajemenProfil();


            return DataTable::of($builder)

                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idmanajemenprofil . '\',\'' . $row->nama_manajemen . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idmanajemenprofil . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $idmanajemen = $this->request->getVar('manajemen_id');
        $jabatan = $this->request->getVar('jabatan');
        $nama = $this->request->getVar('nama');
        $nip = $this->request->getVar('nip');
        $tempatlahir = $this->request->getVar('tempatlahir');
        $tanggallahir = $this->request->getVar('tanggallahir');
        $pendidikan = $this->request->getVar('pendidikan');
        $pangkat = $this->request->getVar('pangkat');
        $profilsingkat = $this->request->getVar('profil_singkat');
        $riwayatpendidikan = $this->request->getVar('riwayat_pendidikan');

        $rules = $this->validate([
            'manajemen_id' => [
                'label' => 'Nama Ruangan Manajemen',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh '
                ]
            ],

            'jabatan' => [
                'label' => 'Jabatan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'nama' => [
                'label' => 'Nama Manajemen',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'nip' => [
                'label' => 'NIP',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'tempatlahir' => [
                'label' => 'Tempat Lahir',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'tanggallahir' => [
                'label' => 'Tempat Lahir',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'pendidikan' => [
                'label' => 'Pendidikan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'pangkat' => [
                'label' => 'Pangkat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'profil_singkat' => [
                'label' => 'Profil Singkat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],

            'gambar' => [
                'label' => 'Gambar Manajemen',
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
                'error_manajemen_id' => $validation->getError('manajemen_id'),
                'error_jabatan' => $validation->getError('jabatan'),
                'error_nama' => $validation->getError('nama'),
                'error_nip' => $validation->getError('nip'),
                'error_tempatlahir' => $validation->getError('tempatlahir'),
                'error_tanggallahir' => $validation->getError('tanggallahir'),
                'error_pendidikan' => $validation->getError('pendidikan'),
                'error_pangkat' => $validation->getError('pangkat'),
                'error_profil_singkat' => $validation->getError('profil_singkat'),
                'error_gambar' => $validation->getError('gambar'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $fileFoto = $this->request->getFile('gambar');

            $namaFoto = "ManajemenProfil" . '_' . $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'manajemenprofil', $namaFoto);

            $this->manajemenprofil->insert([
                'manajemen_id' => $idmanajemen,
                'jabatan' => $jabatan,
                'nama' => $nama,
                'nip' => $nip,
                'tempatlahir' => $tempatlahir,
                'tanggallahir' => $tanggallahir,
                'pendidikan' => $pendidikan,
                'pangkat' => $pangkat,
                'profil_singkat' => $profilsingkat,
                'rtiwayat_pendidikan' => $riwayatpendidikan,
                'gambar' => $namaFoto,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data Manajemen Profil Berhasil Di Tambahkan');
            return redirect()->to('/manajemenprofils');
        }
    }

    public function edit($id = null)
    {
        // $data['manajemen'] = $this->manajemen->findAll();
        $data['manajemen'] = $this->manajemen->orderBy('idmanajemen', 'asc')->where('status', 'Y')->findAll();
        $data['manajemenprofil'] = $this->manajemenprofil->find($id);
        return view('backend/manajemenprofil/edit', $data);
    }

    public function update()
    {

        $idManajemenprofil = $this->request->getVar('idmanajemenprofil');
        $idmanajemen = $this->request->getVar('manajemen_id');
        $jabatan = $this->request->getVar('jabatan');
        $nama = $this->request->getVar('nama');
        $nip = $this->request->getVar('nip');
        $tempatlahir = $this->request->getVar('tempatlahir');
        $tanggallahir = $this->request->getVar('tanggallahir');
        $pendidikan = $this->request->getVar('pendidikan');
        $pangkat = $this->request->getVar('pangkat');
        $profilsingkat = $this->request->getVar('profil_singkat');
        $riwayatpendidikan = $this->request->getVar('riwayat_pendidikan');

        $gambar = $this->request->getFile('gambar');

        $rules = [
            'manajemen_id' => [
                'label' => 'Nama Ruangan Manajemen',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh '
                ]
            ],

            'jabatan' => [
                'label' => 'Jabatan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'nama' => [
                'label' => 'Nama Manajemen',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'nip' => [
                'label' => 'NIP',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'tempatlahir' => [
                'label' => 'Tempat Lahir',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'tanggallahir' => [
                'label' => 'Tempat Lahir',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'pendidikan' => [
                'label' => 'Pendidikan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'pangkat' => [
                'label' => 'Pangkat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
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
                'error_manajemen_id' => $validation->getError('manajemen_id'),
                'error_jabatan' => $validation->getError('jabatan'),
                'error_nama' => $validation->getError('nama'),
                'error_nip' => $validation->getError('nip'),
                'error_tempatlahir' => $validation->getError('tempatlahir'),
                'error_tanggallahir' => $validation->getError('tanggallahir'),
                'error_pendidikan' => $validation->getError('pendidikan'),
                'error_pangkat' => $validation->getError('pangkat'),
                'error_gambar' => $validation->getError('gambar'),
            ]);

            return redirect()->back()->withInput();
        } else {

            // Menghapus foto lama jika ada foto baru diunggah
            if ($gambar->isValid() && !$gambar->hasMoved()) {
                $manajemenprofil = $this->manajemenprofil->find($idManajemenprofil);
                if ($manajemenprofil['gambar'] !== null) {
                    $oldFotoPath = FCPATH . 'manajemenprofil/' . $manajemenprofil['gambar'];
                    if (file_exists($oldFotoPath)) {
                        unlink($oldFotoPath);
                    }
                }

                $newFotoName = "ManajemenProfil" . '_' . $gambar->getRandomName();
                $gambar->move(FCPATH . 'manajemenprofil', $newFotoName);

                // Update data manajemenprofil dengan foto baru
                $this->manajemenprofil->update($idManajemenprofil, [
                    'manajemen_id' => $idmanajemen,
                    'jabatan' => $jabatan,
                    'nama' => $nama,
                    'nip' => $nip,
                    'tempatlahir' => $tempatlahir,
                    'tanggallahir' => $tanggallahir,
                    'pendidikan' => $pendidikan,
                    'pangkat' => $pangkat,
                    'profil_singkat' => $profilsingkat,
                    'riwayat_pendidikan' => $riwayatpendidikan,
                    'gambar' => $newFotoName,
                ]);
            } else {
                // Jika tidak ada foto baru diunggah, update data manajemenprofil tanpa foto
                $this->manajemenprofil->update($idManajemenprofil, [
                    'manajemen_id' => $idmanajemen,
                    'jabatan' => $jabatan,
                    'nama' => $nama,
                    'nip' => $nip,
                    'tempatlahir' => $tempatlahir,
                    'tanggallahir' => $tanggallahir,
                    'pendidikan' => $pendidikan,
                    'profil_singkat' => $profilsingkat,
                    'riwayat_pendidikan' => $riwayatpendidikan,
                    'pangkat' => $pangkat,
                ]);
            }

            session()->setFlashdata('success', 'Data Manajemen Profil Berhasil Di Update');
            return redirect()->to('/manajemenprofils');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $cekReferensi = $this->manajemenprofil->find($id);

            if ($cekReferensi) {
                // Menghapus foto jika ada
                if ($cekReferensi['gambar'] !== null) {
                    $fotoPath = FCPATH . 'manajemenprofil/' . $cekReferensi['gambar'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }

                // Menghapus data dari database
                $this->manajemenprofil->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
