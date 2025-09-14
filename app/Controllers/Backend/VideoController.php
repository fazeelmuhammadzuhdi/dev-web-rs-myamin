<?php

namespace App\Controllers\Backend;

use App\Models\Video;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class VideoController extends BaseController
{
    protected $video;

    public function __construct()
    {
        $this->video = new Video();
        helper('slug');
    }

    public function index()
    {
        $data['title'] = 'Video';

        return view('backend/video/index', $data);
    }

    public function create()
    {
        return view('backend/video/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->video->select('idvideo,judul,tanggal,link,status,thumbnail')->orderBy('tanggal', 'DESC');


            return DataTable::of($builder)
                ->edit('status', function ($row) {
                    if ($row->status == 'PB') {
                        return '<span class="badge badge-success">Publish</span>';
                    } else {
                        return '<span class="badge badge-danger">Belum Publish</span>';
                    }
                })
                ->edit('thumbnail', function ($row) {
                    if ($row->thumbnail !== null) {
                        $imageUrl = base_url('video/' . $row->thumbnail);
                        return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" width="100" height="80"></a>';
                    } else {
                        return '';
                    }
                })
                ->edit('link', function ($row) {
                    if ($row->link !== null) {
                        return '<a href="' . $row->link . '" target="_blank">' . $row->judul . '</a>';
                    } else {
                        return '';
                    }
                })
                ->edit('tanggal', function ($row) {
                    return date('d M Y', strtotime($row->tanggal));
                })
                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" link="Hapus Data" onclick="hapus(\'' . $row->idvideo . '\',\'' . $row->judul . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" link="Edit Data" onclick="edit(\'' . $row->idvideo . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $tanggal = $this->request->getVar('tanggal');
        $link = $this->request->getVar('link');
        $judul = $this->request->getVar('judul');
        $status = $this->request->getVar('status');

        $rules = $this->validate([
            'tanggal' => [
                'label' => 'Tanggal Video',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'link' => [
                'label' => 'Link Video',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'judul' => [
                'label' => 'Judul Video',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_link' => $validation->getError('link'),
                'error_judul' => $validation->getError('judul'),
                'error_tanggal' => $validation->getError('tanggal'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $fileFoto = $this->request->getFile('thumbnail');
            $namaFoto = null;

            if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
                $namaFoto = "vidtumb" . '_' . $fileFoto->getRandomName();
                $fileFoto->move(FCPATH . 'video', $namaFoto);
            }

            $this->video->insert([
                'tanggal' => $tanggal,
                'judul' => $judul,
                'slug' => createSlug($judul),
                'status' => $status ? $status : 'PB',
                'link' => $link,
                'thumbnail' => $namaFoto,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata('success', 'Data Video Berhasil Di Tambahkan');
            return redirect()->to('/videos');
        }
    }

    public function edit($id = null)
    {
        $data['video'] = $this->video->find($id);
        return view('backend/video/edit', $data);
    }



    public function update()
    {
        $idVideo = $this->request->getVar('idvideo');
        $judul = $this->request->getVar('judul');
        $link = $this->request->getVar('link');
        $tanggal = $this->request->getVar('tanggal');
        $status = $this->request->getVar('status');
        $thumbnail = $this->request->getFile('thumbnail');


        $rules = [
            'judul' => [
                'label' => 'Judul  Video',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'link' => [
                'label' => 'Link Video',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'tanggal' => [
                'label' => 'Tanggal Video',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
        ];

        if ($thumbnail->isValid() && !$thumbnail->hasMoved()) {
            // Jika file thumbnail ada dan valid, tambahkan aturan validasi
            $rules['thumbnail'] = 'uploaded[thumbnail]|mime_in[thumbnail,image/jpeg,image/png]|max_size[thumbnail,1024]';
        } else {
            // Jika thumbnail tidak diupload, tidak perlu menambahkan aturan 'uploaded'
            $rules['thumbnail'] = 'mime_in[thumbnail,image/jpeg,image/png]|max_size[thumbnail,1024]';
        }


        $validation = \Config\Services::validation();
        $isValid = $validation->withRequest($this->request)->setRules($rules)->run();

        if (!$isValid) {
            session()->setFlashData([
                'error_link' => $validation->getError('link'),
                'error_judul' => $validation->getError('judul'),
                'error_thumbnail' => $validation->getError('thumbnail'),
                'error_tanggal' => $validation->getError('tanggal'),
            ]);

            return redirect()->back()->withInput();
        } else {

            // Menghapus foto lama jika ada foto baru diunggah
            if ($thumbnail->isValid() && !$thumbnail->hasMoved()) {
                $video = $this->video->find($idVideo);
                if ($video['thumbnail'] !== null) {
                    $oldFotoPath = FCPATH . 'video/' . $video['thumbnail'];
                    if (file_exists($oldFotoPath)) {
                        unlink($oldFotoPath);
                    }
                }

                $newFotoName = "vidtumb" . '_' . $thumbnail->getRandomName();
                $thumbnail->move(FCPATH . 'video', $newFotoName);

                $this->video->update($idVideo, [
                    'judul' => $judul,
                    'link' => $link,
                    'tanggal' => $tanggal,
                    'slug' => createSlug($judul),
                    'status' => $status ? $status : 'PB',
                    'thumbnail' => $newFotoName,
                ]);
            } else {
                $this->video->update($idVideo, [
                    'judul' => $judul,
                    'link' => $link,
                    'tanggal' => $tanggal,
                    'slug' => createSlug($judul),
                    'status' => $status ? $status : 'PB',
                ]);
            }

            session()->setFlashdata('success', 'Data Video Berhasil Di Update');
            return redirect()->to('/videos');
        }
    }


    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $cekReferensi = $this->video->find($id);

            if ($cekReferensi) {
                // Menghapus foto jika ada
                if ($cekReferensi['thumbnail'] !== null) {
                    $fotoPath = FCPATH . 'video/' . $cekReferensi['thumbnail'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }

                // Menghapus data dari database
                $this->video->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
