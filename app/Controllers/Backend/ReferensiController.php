<?php

namespace App\Controllers\Backend;

use App\Models\Referensi;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class ReferensiController extends BaseController
{
    protected $referensi;

    public function __construct()
    {
        $this->referensi = new Referensi();
    }

    public function index()
    {
        $data['title'] = 'Referensi';

        return view('backend/referensi/index', $data);
    }

    public function create()
    {

        return view('backend/referensi/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {

            $builder = $this->referensi->getDataReferensi();

            return DataTable::of($builder)
                ->edit('gambar', function ($row) {
                    if ($row->gambar !== null) {
                        $imageUrl = base_url('referensi/' . $row->gambar);
                        return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" width="100" height="100"></a>';
                    } else {
                        return '';
                    }
                })
                ->edit('link', function ($row) {
                    if ($row->link !== null) {
                        return '<a href="' . $row->link . '" target="_blank">' . $row->link . '</a>';
                    } else {
                        return '';
                    }
                })
                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idreferensi . '\',\'' . $row->judul . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idreferensi . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $judul = $this->request->getVar('judul');
        $pengarang = $this->request->getVar('pengarang');
        $bahasa = $this->request->getVar('bahasa');
        $kategori = $this->request->getVar('kategori');
        $penerbit = $this->request->getVar('penerbit');
        $tahun = $this->request->getVar('tahun');
        $deskripsi = $this->request->getVar('deskripsi');
        $link = $this->request->getVar('link');

        $rules = $this->validate([
            'judul' => [
                'label' => 'Judul',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'pengarang' => [
                'label' => 'Nama Pengarang',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'bahasa' => [
                'label' => 'Bahasa',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'kategori' => [
                'label' => 'Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'penerbit' => [
                'label' => 'Penerbit',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'tahun' => [
                'label' => 'Tahun',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'deskripsi' => [
                'label' => 'Deskripsi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'link' => [
                'label' => 'Link Referensi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'gambar' => [
                'label' => 'Gambar Referensi',
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
                'error_judul' => $validation->getError('judul'),
                'error_pengarang' => $validation->getError('pengarang'),
                'error_bahasa' => $validation->getError('bahasa'),
                'error_kategori' => $validation->getError('kategori'),
                'error_penerbit' => $validation->getError('penerbit'),
                'error_tahun' => $validation->getError('tahun'),
                'error_deskripsi' => $validation->getError('deskripsi'),
                'error_link' => $validation->getError('link'),
                'error_gambar' => $validation->getError('gambar'),

            ]);
            return redirect()->back()->withInput();
        } else {
            $fileFoto = $this->request->getFile('gambar');

            $namaFoto = "Referensi" . '_' . $fileFoto->getRandomName();
            // Pindahkan file foto ke folder tujuan (public/banner)
            $fileFoto->move(FCPATH . 'referensi', $namaFoto);

            $this->referensi->insert([
                'judul' => $judul,
                'pengarang' => $pengarang,
                'bahasa' => $bahasa,
                'kategori' => $kategori,
                'penerbit' => $penerbit,
                'tahun' => $tahun,
                'deskripsi' => $deskripsi,
                'link' => $link,
                'gambar' => $namaFoto,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data Referensi Berhasil Di Tambahkan');
            return redirect()->to('/referensis');
        }
    }

    public function edit($id = null)
    {
        $data['referensi'] = $this->referensi->find($id);
        return view('backend/referensi/edit', $data);
    }

    public function update()
    {

        $idReferensi = $this->request->getVar('idreferensi');
        $judul = $this->request->getVar('judul');
        $pengarang = $this->request->getVar('pengarang');
        $bahasa = $this->request->getVar('bahasa');
        $kategori = $this->request->getVar('kategori');
        $penerbit = $this->request->getVar('penerbit');
        $tahun = $this->request->getVar('tahun');
        $deskripsi = $this->request->getVar('deskripsi');
        $link = $this->request->getVar('link');
        $gambar = $this->request->getFile('gambar');


        $rules = [
            'judul' => [
                'label' => 'Judul',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'pengarang' => [
                'label' => 'Nama Pengarang',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'bahasa' => [
                'label' => 'Bahasa',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'kategori' => [
                'label' => 'Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'penerbit' => [
                'label' => 'Penerbit',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'tahun' => [
                'label' => 'Tahun',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'deskripsi' => [
                'label' => 'Deskripsi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'link' => [
                'label' => 'Link Referensi',
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
            session()->setFlashData([
                'error_judul' => $validation->getError('judul'),
                'error_pengarang' => $validation->getError('pengarang'),
                'error_bahasa' => $validation->getError('bahasa'),
                'error_kategori' => $validation->getError('kategori'),
                'error_penerbit' => $validation->getError('penerbit'),
                'error_tahun' => $validation->getError('tahun'),
                'error_deskripsi' => $validation->getError('deskripsi'),
                'error_link' => $validation->getError('link'),
                'error_gambar' => $validation->getError('gambar'),
            ]);
            return redirect()->back()->withInput();
        } else {
            if ($gambar->isValid() && !$gambar->hasMoved()) {
                $referensi = $this->referensi->find($idReferensi);
                if ($referensi['gambar'] !== null) {
                    $oldFotoPath = FCPATH . 'referensi/' . $referensi['gambar'];
                    if (file_exists($oldFotoPath)) {
                        unlink($oldFotoPath);
                    }
                }

                $newFotoName = "Referensi" . '_' . $gambar->getRandomName();
                $gambar->move(FCPATH . 'referensi', $newFotoName);

                $data = [
                    'judul' => $judul,
                    'pengarang' => $pengarang,
                    'bahasa' => $bahasa,
                    'kategori' => $kategori,
                    'penerbit' => $penerbit,
                    'tahun' => $tahun,
                    'deskripsi' => $deskripsi,
                    'link' => $link,
                    'gambar' => $newFotoName
                ];

                // Update data banner dengan foto baru
                $this->referensi->update($idReferensi, $data);
            } else {

                $data = [
                    'judul' => $judul,
                    'pengarang' => $pengarang,
                    'bahasa' => $bahasa,
                    'kategori' => $kategori,
                    'penerbit' => $penerbit,
                    'tahun' => $tahun,
                    'deskripsi' => $deskripsi,
                    'link' => $link,
                ];

                $this->referensi->update($idReferensi, $data);
            }

            session()->setFlashdata('success', 'Data Referensi Berhasil Di Update');
            return redirect()->to('/referensis');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $cekReferensi = $this->referensi->find($id);

            if ($cekReferensi) {
                // Menghapus foto jika ada
                if ($cekReferensi['gambar'] !== null) {
                    $fotoPath = FCPATH . 'referensi/' . $cekReferensi['gambar'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }

                // Menghapus data dari database
                $this->referensi->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
