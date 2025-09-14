<?php

namespace App\Controllers\Backend;

use App\Models\Berita;
use App\Models\Kategori;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class BeritaController extends BaseController
{
    protected $berita;
    protected $kategori;

    public function __construct()
    {
        $this->berita = new Berita();
        $this->kategori = new Kategori();
        // slug
        helper('slug');
    }



    public function index()
    {
        $data['title'] = 'Berita';
        return view('backend/berita/index', $data);
    }

    public function create()
    {
        // $data['userId'] = session()->get('idUser');

        $data['kategori'] = $this->kategori->where('status', 'Y')->findAll();
        return view('backend/berita/create', $data);
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->berita->getBerita();


            return DataTable::of($builder)
                ->edit('status', function ($row) {
                    if ($row->status == 'PB') {
                        return '<span class="badge badge-success" style="font-size: 14px;">Publish</span>';
                    } else {
                        return '<span class="badge badge-danger" style="font-size: 14px;">Belum Publish</span>';
                    }
                })

                ->edit('nama', function ($row) {
                    if ($row->nama) {
                        return '<span class="badge badge-info" style="font-size: 14px;">' . esc($row->nama) . '</span>';
                    } else {
                        return '<span class="badge badge-warning" style="font-size: 14px;">Administrator</span>';
                    }
                })

                ->edit('title', function ($row) {
                    if ($row->title == "Agenda") {
                        return '<span class="badge badge-primary text-nowrap" style="font-size: 13px;">' . esc($row->title) . '</span>';
                    } elseif ($row->title == "Berita RS") {
                        return '<span class="badge badge-danger text-nowrap" style="font-size: 13px;">' . esc($row->title) . '</span>';
                    } elseif ($row->title == "Informasi Asuransi") {
                        return '<span class="badge badge-success text-nowrap" style="font-size: 13px;">' . esc($row->title) . '</span>';
                    } else {
                        return '<span class="badge badge-secondary text-nowrap" style="font-size: 13px;">' . esc($row->title) . '</span>';
                    }
                })


                ->edit('gambar', function ($row) {
                    if ($row->gambar !== null) {
                        $imageUrl = base_url('berita/' . $row->gambar);
                        return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" width="150" height="100"></a>';
                    } else {
                        return '';
                    }
                })
                ->edit('tanggal', function ($row) {
                    return '<span class="text-nowrap">' . tanggal_indonesia($row->tanggal) . '</span>';
                })


                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" judul="Hapus Data" onclick="hapus(\'' . $row->idberita . '\',\'' . $row->judul . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" judul="Edit Data" onclick="edit(\'' . $row->idberita . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function update()
    {

        $idBerita = $this->request->getVar('idberita');
        $kategori_id = $this->request->getVar('kategori_id');
        $tanggal = $this->request->getVar('tanggal');
        $judul = $this->request->getVar('judul');
        $konten = trim($this->request->getVar('konten'));
        $status = $this->request->getVar('status');
        $userId = session()->get('idUser') ?? 1;
        $gambar = $this->request->getFile('gambar');


        $rules = [

            'judul' => [
                'label' => 'Judul Berita',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'konten' => [
                'label' => 'Konten Berita',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'gambar' => [
                'label' => 'Gambar Berita',
                'rules' => 'max_size[gambar,1024]|mime_in[gambar,image/jpeg,image/png,image/jpg]',
                'errors' => [
                    'max_size' => 'Ukuran {field} maksimum 1MB',
                    'mime_in' => 'Format {field} harus JPEG, PNG atau JPG'
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
                'error_judul' => $validation->getError('judul'),
                'error_konten' => $validation->getError('konten'),
                'error_gambar' => $validation->getError('gambar')
            ]);

            return redirect()->back()->withInput();
        } else {
            // Menghapus foto lama jika ada foto baru diunggah
            if ($gambar->isValid() && !$gambar->hasMoved()) {
                $berita = $this->berita->find($idBerita);
                if ($berita['gambar'] !== null) {
                    $oldFotoPath = FCPATH . 'berita/' . $berita['gambar'];
                    if (file_exists($oldFotoPath)) {
                        // Pastikan oldFotoPath adalah file, bukan direktori
                        if (!is_dir($oldFotoPath)) {
                            unlink($oldFotoPath);
                        }
                    }

                    // Hapus thumbnail lama
                    $oldThumbnailPath = FCPATH . 'berita/' . $berita['thumbnail'];
                    if (file_exists($oldThumbnailPath)) {
                        if (!is_dir($oldThumbnailPath)) {
                            unlink($oldThumbnailPath);
                        }
                    }
                }

                $newFotoName = "Berita" . '_' . $gambar->getRandomName();
                $gambar->move(FCPATH . 'berita', $newFotoName);

                $namaThumbnail = str_replace('.', '_thumb.', $newFotoName);
                $imageService = service('image');

                $image = $imageService->withFile(FCPATH . 'berita/' . $newFotoName);
                $image->resize(450, 250, true, 'center');
                $image->save(FCPATH . 'berita/' . $namaThumbnail);


                // Update data berita dengan foto baru
                $this->berita->update($idBerita, [
                    'judul' => $judul,
                    'user_id' => $userId,
                    'tanggal' => $tanggal,
                    'konten' => $konten,
                    'kategori_id' => $kategori_id,
                    'status' => $status,
                    'gambar' => $newFotoName,
                    'thumbnail' => $namaThumbnail,
                    'slug' => createSlug($judul),
                ]);
            } else {
                // Jika tidak ada foto baru diunggah, update data berita tanpa foto
                $this->berita->update($idBerita, [
                    'judul' => $judul,
                    'user_id' => $userId,
                    'tanggal' => $tanggal,
                    'konten' => $konten,
                    'kategori_id' => $kategori_id,
                    'status' => $status,
                    'slug' => createSlug($judul),
                ]);
            }

            session()->setFlashdata('success', 'Data Berita Berhasil Di Update');
            return redirect()->to('/beritas');
        }
    }

    public function save()
    {
        $kategori_id = $this->request->getVar('kategori_id');
        $tanggal = $this->request->getVar('tanggal');
        $judul = $this->request->getVar('judul');
        $konten = $this->request->getVar('konten');
        $status = $this->request->getVar('status');
        $userId = session()->get('idUser');

        $rules = $this->validate([
            'judul' => [
                'label' => 'Judul Berita',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'tanggal' => [
                'label' => 'Tanggal Berita',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'status' => [
                'label' => 'Status Berita',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'konten' => [
                'label' => 'Konten Berita',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'kategori_id' => [
                'label' => 'Kategori Berita',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'gambar' => [
                'label' => 'Gambar Berita',
                'rules' => 'uploaded[gambar]|max_size[gambar,1024]|mime_in[gambar,image/jpeg,image/png,image/jpg]',
                'errors' => [
                    'uploaded' => '{field} tidak boleh kosong',
                    'max_size' => 'Ukuran {field} maksimum 1MB',
                    'mime_in' => 'Format {field} harus JPEG, PNG atau JPG'
                ]
            ],
        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_judul' => $validation->getError('judul'),
                'error_tanggal' => $validation->getError('tanggal'),
                'error_konten' => $validation->getError('konten'),
                'error_gambar' => $validation->getError('gambar'),
                'error_kategori_id' => $validation->getError('kategori_id'),
                'error_status' => $validation->getError('status'),
            ]);
            return redirect()->back()->withInput();
        } else {

            $fileFoto = $this->request->getFile('gambar');
            $namaFoto = "Berita" . '_' . $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'berita', $namaFoto);


            $namaThumbnail = str_replace('.', '_thumb.', $namaFoto);

            $imageService = service('image');

            $image = $imageService->withFile(FCPATH . 'berita/' . $namaFoto);
            $image->resize(450, 225, true, 'center');

            $image->save(FCPATH . 'berita/' . $namaThumbnail, 90);

            // Insert data to database
            $this->berita->insert([
                'judul' => $judul,
                'user_id' => $userId,
                'tanggal' => $tanggal,
                'konten' => $konten,
                'kategori_id' => $kategori_id,
                'status' => $status,
                'gambar' => $namaFoto,
                'thumbnail' => $namaThumbnail,
                'slug' => createSlug($judul),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data Berita Berhasil Ditambahkan');
            return redirect()->to('/beritas');
        }
    }


    public function edit($id = null)
    {
        $data['beritas'] = $this->berita->find($id);
        $data['kategori'] = $this->kategori->where('status', 'Y')->findAll();
        return view('backend/berita/edit', $data);
    }
    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $cekReferensi = $this->berita->find($id);

            if ($cekReferensi) {
                // Menghapus foto jika ada
                if ($cekReferensi['gambar'] !== null) {
                    $fotoPath = FCPATH . 'berita/' . $cekReferensi['gambar'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }

                    $thumbnailPath = FCPATH . 'berita/' . $cekReferensi['thumbnail'];
                    if (file_exists($thumbnailPath)) {
                        unlink($thumbnailPath);
                    }
                }

                // Menghapus data dari database
                $this->berita->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
