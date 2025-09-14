<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Album;
use DOMDocument;
use Hermawan\DataTables\DataTable;

class AlbumController extends BaseController
{
    protected $album;

    public function __construct()
    {
        $this->album = new Album();
        helper('slug');
    }

    public function index()
    {
        $data['title'] = 'Album';

        return view('backend/album/index', $data);
    }

    public function create()
    {
        return view('backend/album/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->album->join('user', 'album.user_id = user.iduser')->select('idalbum,judul,tanggal,keterangan,album.status,nama')->orderBy('tanggal', 'desc')->orderBy('idalbum', 'DESC');

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

                ->edit('tanggal', function ($row) {
                    return '<span class="text-nowrap">' . tanggal_indonesia($row->tanggal) . '</span>';
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

                    <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $row->idalbum . '\',\'' . $row->judul . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $row->idalbum . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $judul = $this->request->getVar('judul');
        $tanggal = $this->request->getVar('tanggal');
        $status = $this->request->getVar('status');
        $userId = session()->get('idUser');
        $keterangan = $this->request->getVar('keterangan');

        $rules = $this->validate([
            'judul' => [
                'label' => 'Judul Album',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'tanggal' => [
                'label' => 'Tanggal Album',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'status' => [
                'label' => 'Status Album',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Album',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_judul' => $validation->getError('judul'),
                'error_tanggal' => $validation->getError('tanggal'),
                'error_keterangan' => $validation->getError('keterangan'),
                'error_status' => $validation->getError('status'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->album->insert([
                'judul' => $judul,
                'tanggal' => $tanggal,
                'status' => $status,
                'keterangan' => $keterangan,
                'user_id' => $userId,
                'slug' => createSlug($judul),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata('success', 'Data Berhasil Di Tambahkan');
            return redirect()->to('/album');
        }
    }

    public function edit($id = null)
    {
        $data['album'] = $this->album->find($id);
        return view('backend/album/edit', $data);
    }

    public function update()
    {

        $idAlbum = $this->request->getVar('idalbum');
        $userId = session()->get('idUser');
        $judul = $this->request->getVar('judul');
        $tanggal = $this->request->getVar('tanggal');
        $status = $this->request->getVar('status');
        $keterangan = $this->request->getVar('keterangan');

        $rules = $this->validate([
            'judul' => [
                'label' => 'Judul Album',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'tanggal' => [
                'label' => 'Tanggal Album',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'status' => [
                'label' => 'Status Album',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Album',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_judul' => $validation->getError('judul'),
                'error_tanggal' => $validation->getError('tanggal'),
                'error_keterangan' => $validation->getError('keterangan'),
                'error_status' => $validation->getError('status'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->album->update($idAlbum, [
                'judul' => $judul,
                'tanggal' => $tanggal,
                'status' => $status,
                'keterangan' => $keterangan,
                'user_id' => $userId,
                'slug' => createSlug($judul),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata('success', "Data Berhasil Di Update");
            return redirect()->to('/album');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $idAlbum = $this->album->find($id);

            if ($idAlbum) {
                $this->album->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
