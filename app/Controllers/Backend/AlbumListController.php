<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\Album;
use App\Models\AlbumList;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class AlbumListController extends BaseController
{
    protected $albumlist;
    protected $album;

    public function __construct()
    {
        $this->albumlist = new AlbumList();
        $this->album = new Album();
    }

    public function index()
    {
        $data['title'] = 'Album List';
        return view('backend/albumlist/index', $data);
    }

    public function create()
    {
        $data['album'] = $this->album->orderBy('idalbum', 'desc')->findAll();

        // $data['album'] = $this->album->where('status', 'PB')->findAll();
        return view('backend/albumlist/create', $data);
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->albumlist->getAlbumList();
            return DataTable::of($builder)
                ->edit('status', function ($row) {
                    if ($row->status == 'PB') {
                        return '<span class="badge badge-success">Publish</span>';
                    } else {
                        return '<span class="badge badge-danger">Belum Publish</span>';
                    }
                })
                ->edit('tanggal', function ($row) {
                    return date('d M Y', strtotime($row->tanggal));
                })

                ->edit('nama', function ($row) {
                    if ($row->nama) {
                        return '<span class="badge badge-info" style="font-size: 14px;">' . esc($row->nama) . '</span>';
                    } else {
                        return '<span class="badge badge-warning" style="font-size: 14px;">Administrator</span>';
                    }
                })

                // ->edit('gambar', function ($row) {
                //     if ($row->gambar !== null) {
                //         $gambarArray = explode(',', $row->gambar);
                //         $imageUrl = base_url('albumlist/' . $gambarArray[0]);
                //     } else {
                //         $imageUrl = base_url('albumlist/noimage.png');
                //     }

                //     return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" width="130" height="80" class="image-preview"></a>';
                // })
                ->add('action', function ($row) {
                    return  '<div class="d-flex" role="group">
                <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $row->idalbumlist . '\',\'' . $row->judul . '\')">
                    <i class="feather icon-trash-2"></i>
                </button>
                <button type="button" class="btn btn-round btn-primary mx-1" title="Edit Data" onclick="edit(\'' . $row->idalbumlist . '\')">
                    <i class="feather icon-edit"></i>
                </button>
                <button type="button" class="btn btn-round btn-info mx-1" title="Detail Data" onclick="detail(\'' . $row->idalbumlist . '\')">
                    <i class="feather icon-info"></i>
                </button>
            </div>';
                }, 'last')
                ->toJson();
        }
    }

    public function save()
    {
        $idAlbum = $this->request->getVar('album_id');
        $keterangan = $this->request->getVar('keterangan');

        $rules = $this->validate([
            'album_id' => [
                'label' => 'Nama Album',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Albumlist',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'gambar' => [
                'label' => 'Gambar Albumlist',
                'rules' => 'uploaded[gambar]|max_size[gambar,1024]|mime_in[gambar,image/jpeg,image/png,image/jpg]',
                'errors' => [
                    'uploaded' => '{field} tidak boleh kosong',
                    'max_size' => 'Ukuran {field} maksimum 1MB',
                    'mime_in' => 'Format {field} harus JPEG, PNG, atau JPG'
                ]
            ]
        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_album_id' => $validation->getError('album_id'),
                'error_keterangan' => $validation->getError('keterangan'),
                'error_gambar' => $validation->getError('gambar'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $files = $this->request->getFiles('gambar');

            foreach ($files['gambar'] as $file) {
                // Pastikan file yang diunggah adalah file gambar
                if ($file->isValid() && in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                    // Generate nama unik untuk file
                    $namaFoto = "Albumlist_" . $file->getRandomName();
                    // Pindahkan file foto ke folder tujuan (public/albumlist)
                    $file->move(FCPATH . 'albumlist', $namaFoto);

                    $this->albumlist->insert([
                        'album_id' => $idAlbum,
                        'keterangan' => $keterangan,
                        'gambar' => $namaFoto,
                        'thumbnail' => $namaFoto,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                } else {
                    // File tidak valid, lakukan penanganan kesalahan di sini
                    session()->setFlashdata('error_gambar', 'File yang diunggah tidak valid');
                    return redirect()->back()->withInput();
                }
            }

            session()->setFlashdata('success', 'Data Album List Berhasil Ditambahkan');
            return redirect()->to('/albumlists');
        }
    }

    public function edit($id = null)
    {
        $data['album'] = $this->album->orderBy('idalbum', 'desc')->findAll();
        // $data['album'] = $this->album->where('status', 'PB')->findAll();

        $data['albumlists'] = $this->albumlist->find($id);
        return view('backend/albumlist/edit', $data);
    }

    public function detail($id = null)
    {
        $data['albumlists'] = $this->albumlist->find($id);
        $data['title'] = 'Detail Album List';
        $data['foto'] = $this->albumlist->where('album_id', $data['albumlists']['album_id'])->findAll();
        // dd($data['foto']);


        return view('backend/albumlist/detail', $data);
    }

    public function update()
    {
        $idAlbumlist = $this->request->getVar('idalbumlist');
        $album_id = $this->request->getVar('album_id');
        $keterangan = $this->request->getVar('keterangan');
        $files = $this->request->getFiles();

        $rules = [
            'album_id' => [
                'label' => 'Judul Album',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Album List',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ]
        ];

        if (!empty($files['gambar'])) {
            $rules['gambar'] = 'uploaded[gambar]|mime_in[gambar,image/jpeg,image/png,image/jpg]|max_size[gambar,1024]';
        }

        $validation = \Config\Services::validation();
        $isValid = $validation->withRequest($this->request)->setRules($rules)->run();

        if (!$isValid) {
            session()->setFlashData([
                'error_album_id' => $validation->getError('album_id'),
                'error_keterangan' => $validation->getError('keterangan'),
                'error_gambar' => $validation->getError('gambar'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $albumlist = $this->albumlist->find($idAlbumlist);
            $oldGambar = explode(',', $albumlist['gambar']);

            if (!empty($files['gambar'])) {
                foreach ($oldGambar as $oldFile) {
                    $oldFotoPath = FCPATH . 'albumlist/' . $oldFile;
                    if (file_exists($oldFotoPath) && !is_dir($oldFotoPath)) { // Pastikan bukan direktori
                        unlink($oldFotoPath);
                    }
                }

                $newGambarNames = [];
                foreach ($files['gambar'] as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newFotoName = "Albumlist_" . $file->getRandomName();
                        $file->move(FCPATH . 'albumlist', $newFotoName);
                        $newGambarNames[] = $newFotoName;
                    }
                }
                $newGambar = implode(',', $newGambarNames);

                $this->albumlist->update($idAlbumlist, [
                    'album_id' => $album_id,
                    'keterangan' => $keterangan,
                    'gambar' => $newGambar,
                    'thumbnail' => $newGambar,
                ]);
            } else {
                $this->albumlist->update($idAlbumlist, [
                    'album_id' => $album_id,
                    'keterangan' => $keterangan,
                ]);
            }

            session()->setFlashdata('success', 'Data Albumlist Berhasil Di Update');
            return redirect()->to('/albumlists');
        }
    }


    // public function update()
    // {
    //     $idAlbumlist = $this->request->getVar('idalbumlist');
    //     $album_id = $this->request->getVar('album_id');
    //     $keterangan = $this->request->getVar('keterangan');
    //     $files = $this->request->getFiles();

    //     $rules = [
    //         'album_id' => [
    //             'label' => 'Judul Album',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} tidak boleh kosong',
    //             ]
    //         ],
    //         'keterangan' => [
    //             'label' => 'Keterangan Album List',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} tidak boleh kosong',
    //             ]
    //         ]
    //     ];

    //     if (!empty($files['gambar'])) {
    //         $rules['gambar'] = 'uploaded[gambar]|mime_in[gambar,image/jpeg,image/png,image/jpg]|max_size[gambar,1024]';
    //     }

    //     $validation = \Config\Services::validation();
    //     $isValid = $validation->withRequest($this->request)->setRules($rules)->run();

    //     if (!$isValid) {
    //         session()->setFlashData([
    //             'error_album_id' => $validation->getError('album_id'),
    //             'error_keterangan' => $validation->getError('keterangan'),
    //             'error_gambar' => $validation->getError('gambar'),
    //         ]);
    //         return redirect()->back()->withInput();
    //     } else {
    //         $albumlist = $this->albumlist->find($idAlbumlist);
    //         $oldGambar = explode(',', $albumlist['gambar']);

    //         if (!empty($files['gambar'])) {
    //             foreach ($oldGambar as $oldFile) {
    //                 $oldFotoPath = FCPATH . 'albumlist/' . $oldFile;
    //                 if (file_exists($oldFotoPath)) {
    //                     unlink($oldFotoPath);
    //                 }
    //             }

    //             $newGambarNames = [];
    //             foreach ($files['gambar'] as $file) {
    //                 if ($file->isValid() && !$file->hasMoved()) {
    //                     $newFotoName = "Albumlist_" . $file->getRandomName();
    //                     $file->move(FCPATH . 'albumlist', $newFotoName);
    //                     $newGambarNames[] = $newFotoName;
    //                 }
    //             }
    //             $newGambar = implode(',', $newGambarNames);

    //             $this->albumlist->update($idAlbumlist, [
    //                 'album_id' => $album_id,
    //                 'keterangan' => $keterangan,
    //                 'gambar' => $newGambar,
    //                 'thumbnail' => $newGambar,
    //             ]);
    //         } else {
    //             $this->albumlist->update($idAlbumlist, [
    //                 'album_id' => $album_id,
    //                 'keterangan' => $keterangan,
    //             ]);
    //         }

    //         session()->setFlashdata('success', 'Data Albumlist Berhasil Di Update');
    //         return redirect()->to('/albumlists');
    //     }
    // }


    // public function update()
    // {
    //     $idAlbumlist = $this->request->getVar('idalbumlist');
    //     $album_id = $this->request->getVar('album_id');
    //     $keterangan = $this->request->getVar('keterangan');
    //     $files = $this->request->getFiles();

    //     $rules = [
    //         'album_id' => [
    //             'label' => 'Judul Album',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} tidak boleh kosong',
    //             ]
    //         ],
    //         'keterangan' => [
    //             'label' => 'Keterangan Album List',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} tidak boleh kosong',
    //             ]
    //         ]
    //     ];

    //     if ($files['gambar']) {
    //         $rules['gambar'] = 'uploaded[gambar]|mime_in[gambar,image/jpeg,image/png,image/jpg]|max_size[gambar,1024]';
    //     }

    //     $validation = \Config\Services::validation();
    //     $isValid = $validation->withRequest($this->request)->setRules($rules)->run();

    //     if (!$isValid) {
    //         $validation = \Config\Services::validation();
    //         session()->setFlashData([
    //             'error_album_id' => $validation->getError('album_id'),
    //             'error_keterangan' => $validation->getError('keterangan'),
    //             'error_gambar' => $validation->getError('gambar'),
    //         ]);
    //         return redirect()->back()->withInput();
    //     } else {
    //         $albumlist = $this->albumlist->find($idAlbumlist);
    //         $oldGambar = explode(',', $albumlist['gambar']);

    //         if ($files['gambar']) {
    //             foreach ($oldGambar as $oldFile) {
    //                 $oldFotoPath = FCPATH . 'albumlist/' . $oldFile;
    //                 if (file_exists($oldFotoPath)) {
    //                     unlink($oldFotoPath);
    //                 }
    //             }

    //             $newGambarNames = [];
    //             foreach ($files['gambar'] as $file) {
    //                 if ($file->isValid() && !$file->hasMoved()) {
    //                     $newFotoName = "Albumlist_" . $file->getRandomName();
    //                     $file->move(FCPATH . 'albumlist', $newFotoName);
    //                     $newGambarNames[] = $newFotoName;
    //                 }
    //             }
    //             $newGambar = implode(',', $newGambarNames);

    //             $this->albumlist->update($idAlbumlist, [
    //                 'album_id' => $album_id,
    //                 'keterangan' => $keterangan,
    //                 'gambar' => $newGambar,
    //                 'thumbnail' => $newGambar,
    //             ]);
    //         } else {
    //             $this->albumlist->update($idAlbumlist, [
    //                 'album_id' => $album_id,
    //                 'keterangan' => $keterangan,
    //             ]);
    //         }

    //         session()->setFlashdata('success', 'Data Albumlist Berhasil Di Update');
    //         return redirect()->to('/albumlists');
    //     }
    // }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            // Temukan data yang akan dihapus berdasarkan ID
            $cekReferensi = $this->albumlist->find($id);

            if ($cekReferensi) {
                $album_id = $cekReferensi['album_id'];

                // Temukan semua data dengan album_id yang sama
                $referensiList = $this->albumlist->where('album_id', $album_id)->findAll();

                // Hapus file gambar terkait
                foreach ($referensiList as $referensi) {
                    if ($referensi['gambar'] !== null) {
                        $gambarArray = explode(',', $referensi['gambar']);
                        foreach ($gambarArray as $gambar) {
                            $fotoPath = FCPATH . 'albumlist/' . $gambar;
                            if (file_exists($fotoPath)) {
                                unlink($fotoPath);
                            }
                        }
                    }
                }

                // Hapus data dari database
                $this->albumlist->where('album_id', $album_id)->delete();

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            } else {
                $json = [
                    'error' => 'Data tidak ditemukan'
                ];
                echo json_encode($json);
            }
        }
    }


    public function uploadImage()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gambar' => [
                'rules' => 'uploaded[gambar]|max_size[gambar,1024]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Tidak ada file yang diupload',
                    'max_size' => 'Ukuran file maksimal adalah 1MB',
                    'is_image' => 'File yang diupload bukan gambar',
                    'mime_in' => 'Format gambar harus JPG, JPEG, atau PNG',
                ],
            ],
        ]);

        if (!$this->validate($validation->getRules())) {
            return $this->response->setJSON(['success' => false, 'error' => $validation->getError('gambar')]);
        }

        $id = $this->request->getPost('id'); // Mengambil ID dari request jika diperlukan
        $item = $this->albumlist->find($id);

        // Cek jika ada gambar lama dan hapus
        if ($item && !empty($item['gambar'])) {
            $oldImagePath = FCPATH . 'albumlist/' . $item['gambar'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Hapus gambar lama
            }
        }

        $file = $this->request->getFile('gambar');
        $newName = "Albumlist_" . $file->getRandomName();
        $file->move(FCPATH . 'albumlist', $newName);
        $filePath = base_url('albumlist/' . $newName);

        // Simpan nama file baru ke database
        $this->albumlist->update($id, ['gambar' => $newName, 'thumbnail' => $newName]);

        return $this->response->setJSON(['success' => true, 'filePath' => $filePath]);
    }


    public function tambahGambar()
    {
        $idAlbum = $this->request->getVar('album_id');

        $rules = $this->validate([

            'gambar' => [
                'label' => 'Gambar Albumlist',
                'rules' => 'uploaded[gambar]|max_size[gambar,1024]|mime_in[gambar,image/jpeg,image/png,image/jpg]',
                'errors' => [
                    'uploaded' => '{field} tidak boleh kosong',
                    'max_size' => 'Ukuran {field} maksimum 1MB',
                    'mime_in' => 'Format {field} harus JPEG, PNG, atau JPG'
                ]
            ]
        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_gambar' => $validation->getError('gambar'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $files = $this->request->getFiles('gambar');

            foreach ($files['gambar'] as $file) {
                // Pastikan file yang diunggah adalah file gambar
                if ($file->isValid() && in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                    // Generate nama unik untuk file
                    $namaFoto = "Albumlist_" . $file->getRandomName();
                    // Pindahkan file foto ke folder tujuan (public/albumlist)
                    $file->move(FCPATH . 'albumlist', $namaFoto);

                    $this->albumlist->insert([
                        'album_id' => $idAlbum,
                        'gambar' => $namaFoto,
                        'thumbnail' => $namaFoto,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                } else {
                    // File tidak valid, lakukan penanganan kesalahan di sini
                    session()->setFlashdata('error_gambar', 'File yang diunggah tidak valid');
                    return redirect()->back()->withInput();
                }
            }

            session()->setFlashdata('success', 'Data Album List Berhasil Ditambahkan');
            return redirect()->back();
        }
    }

    public function hapusGambar()
    {

        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $data = $this->albumlist->find($id);

            if (!$data) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            }

            // Hapus gambar dari direktori
            $filePath = FCPATH . 'albumlist/' . $data['gambar'];

            if (file_exists($filePath)) {
                unlink($filePath); // Hapus file gambar
            }

            // Hapus data dari database
            $this->albumlist->delete($id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Gambar berhasil dihapus']);
        }

        // Jika bukan request AJAX
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Akses tidak valid'
        ]);
    }
}
