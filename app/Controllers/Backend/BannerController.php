<?php

namespace App\Controllers\Backend;

use App\Models\Banner;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class BannerController extends BaseController
{
    protected $banner;

    public function __construct()
    {
        $this->banner = new Banner();
    }

    public function index()
    {
        $data['title'] = 'Banner';
        return view('backend/banner/index', $data);
    }

    public function create()
    {
        return view('backend/banner/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->banner->select('idbanner,judul,link,gambar,status');


            return DataTable::of($builder)
                ->edit('status', function ($row) {
                    if ($row->status == 'PB') {
                        return '<span class="badge badge-success">Publish</span>';
                    } else {
                        return '<span class="badge badge-danger">Belum Publish</span>';
                    }
                })
                ->edit('gambar', function ($row) {
                    if ($row->gambar !== null) {
                        return '<img src="' . base_url('banner/' . $row->gambar) . '" width="180" height="80">';
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

                    <button type="button" class="btn btn-round btn-danger mx-1" judul="Hapus Data" onclick="hapus(\'' . $row->idbanner . '\',\'' . $row->judul . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" judul="Edit Data" onclick="edit(\'' . $row->idbanner . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $judul = $this->request->getVar('judul');
        $link = $this->request->getVar('link');
        $status = $this->request->getVar('status');

        $rules = $this->validate([

            'judul' => [
                'label' => 'Judul Banner',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'link' => [
                'label' => 'Link Banner',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'gambar' => [
                'label' => 'Gambar Banner',
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
                'error_link' => $validation->getError('link'),
                'error_gambar' => $validation->getError('gambar'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $fileFoto = $this->request->getFile('gambar');

            $namaFoto = "Banner" . '_' . $fileFoto->getRandomName();
            // Pindahkan file foto ke folder tujuan (public/banner)
            $fileFoto->move(FCPATH . 'banner', $namaFoto);

            $this->banner->insert([
                'judul' => $judul,
                'link' => $link,
                'status' => $status ? $status : 'PB',
                'gambar' => $namaFoto,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data Banner Berhasil Di Tambahkan');
            return redirect()->to('/banners');
        }
    }

    public function edit($id = null)
    {
        $data['banners'] = $this->banner->find($id);
        return view('backend/banner/edit', $data);
    }

    public function update()
    {

        $idBanner = $this->request->getVar('idbanner');
        $judul = $this->request->getVar('judul');
        $link = $this->request->getVar('link');
        $gambar = $this->request->getFile('gambar');
        $status = $this->request->getVar('status');


        $rules = [

            'judul' => [
                'label' => 'Judul Banner',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'link' => [
                'label' => 'Link Banner',
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
                'error_judul' => $validation->getError('judul'),
                'error_link' => $validation->getError('link'),
            ]);

            return redirect()->back()->withInput();
        } else {

            // Menghapus foto lama jika ada foto baru diunggah
            if ($gambar->isValid() && !$gambar->hasMoved()) {
                $banner = $this->banner->find($idBanner);
                if ($banner['gambar'] !== null) {
                    $oldFotoPath = FCPATH . 'banner/' . $banner['gambar'];
                    if (file_exists($oldFotoPath)) {
                        unlink($oldFotoPath);
                    }
                }

                $newFotoName = "Banner" . '_' . $gambar->getRandomName();
                $gambar->move(FCPATH . 'banner', $newFotoName);

                // Update data banner dengan foto baru
                $this->banner->update($idBanner, [
                    'judul' => $judul,
                    'link' => $link,
                    'status' => $status ? $status : 'PB',
                    'gambar' => $newFotoName,
                ]);
            } else {
                // Jika tidak ada foto baru diunggah, update data banner tanpa foto
                $this->banner->update($idBanner, [
                    'judul' => $judul,
                    'link' => $link,
                    'status' => $status ? $status : 'PB',
                ]);
            }

            session()->setFlashdata('success', 'Data Banner Berhasil Di Update');
            return redirect()->to('/banners');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $cekReferensi = $this->banner->find($id);

            if ($cekReferensi) {
                // Menghapus foto jika ada
                if ($cekReferensi['gambar'] !== null) {
                    $fotoPath = FCPATH . 'banner/' . $cekReferensi['gambar'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }

                // Menghapus data dari database
                $this->banner->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
