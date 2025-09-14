<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\IndikatorMutu;
use App\Models\IndikatorMutuList;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class IndikatorMutuListController extends BaseController
{
    protected $indikatormutulist;
    protected $indikatormutu;

    public function __construct()
    {
        $this->indikatormutulist = new IndikatorMutuList();
        $this->indikatormutu = new IndikatorMutu();
    }

    public function index()
    {
        $data['title'] = 'Indikator Mutu List';
        return view('backend/indikatormutulist/index', $data);
    }

    public function create()
    {
        $data['indikatormutu'] = $this->indikatormutu->findAll();
        return view('backend/indikatormutulist/create', $data);
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->indikatormutulist->getIndikatorMutuList();


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
                        $imageUrl = base_url('indikatormutulist/' . $row->gambar);
                        return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" width="200" height="60"></a>';
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

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idindikatormutulist . '\',\'' . $row->nama . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idindikatormutulist . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $indikatorMutuId = $this->request->getVar('indikator_mutu_id');
        $keterangan = $this->request->getVar('keterangan');
        $status = $this->request->getVar('status');

        $rules = $this->validate([

            'indikator_mutu_id' => [
                'label' => 'Nama Indikator Mutu',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'keterangan' => [
                'label' => 'Keterangan Indikator Mutu List',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'gambar' => [
                'label' => 'Gambar Indikator Mutu List',
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
                'error_indikator_mutu_id' => $validation->getError('indikator_mutu_id'),
                'error_keterangan' => $validation->getError('keterangan'),
                'error_gambar' => $validation->getError('gambar'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $fileFoto = $this->request->getFile('gambar');

            $namaFoto = "Indikatormutulist" . '_' . $fileFoto->getRandomName();
            // Pindahkan file foto ke folder tujuan (public/indikatormutulist)
            $fileFoto->move(FCPATH . 'indikatormutulist', $namaFoto);

            $this->indikatormutulist->insert([
                'indikator_mutu_id' => $indikatorMutuId,
                'keterangan' => $keterangan,
                'status' => $status ? $status : 'Y',
                'gambar' => $namaFoto,
                'created_at' => date('Y-m-d H:i:s'),
            ]);



            session()->setFlashdata('success', 'Data Indikatormutulist Berhasil Di Tambahkan');
            return redirect()->to('/indikatormutulists');
        }
    }

    public function edit($id = null)
    {
        $data['indikatormutu'] = $this->indikatormutu->findAll();
        $data['indikatormutulists'] = $this->indikatormutulist->find($id);
        return view('backend/indikatormutulist/edit', $data);
    }

    public function update()
    {

        $idIndikatormutulist = $this->request->getVar('idindikatormutulist');
        $indikatorMutuId = $this->request->getVar('indikator_mutu_id');
        $keterangan = $this->request->getVar('keterangan');
        $gambar = $this->request->getFile('gambar');
        $status = $this->request->getVar('status');


        $rules = [

            'indikator_mutu_id' => [
                'label' => 'Nama Indikator Mutu',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'keterangan' => [
                'label' => 'Keterangan Indikator Mutu List',
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
                'error_indikator_mutu_id' => $validation->getError('indikator_mutu_id'),
                'error_keterangan' => $validation->getError('keterangan'),
                'error_gambar' => $validation->getError('gambar'),
            ]);

            return redirect()->back()->withInput();
        } else {

            // Menghapus foto lama jika ada foto baru diunggah
            if ($gambar->isValid() && !$gambar->hasMoved()) {
                $indikatormutulist = $this->indikatormutulist->find($idIndikatormutulist);
                if ($indikatormutulist['gambar'] !== null) {
                    $oldFotoPath = FCPATH . 'indikatormutulist/' . $indikatormutulist['gambar'];
                    if (file_exists($oldFotoPath)) {
                        unlink($oldFotoPath);
                    }
                }

                $newFotoName = "Indikatormutulist" . '_' . $gambar->getRandomName();
                $gambar->move(FCPATH . 'indikatormutulist', $newFotoName);

                // Update data indikatormutulist dengan foto baru
                $this->indikatormutulist->update($idIndikatormutulist, [
                    'indikator_mutu_id' => $indikatorMutuId,
                    'keterangan' => $keterangan,
                    'status' => $status ? $status : 'Y',
                    'gambar' => $newFotoName,
                ]);
            } else {
                // Jika tidak ada foto baru diunggah, update data indikatormutulist tanpa foto
                $this->indikatormutulist->update($idIndikatormutulist, [
                    'indikator_mutu_id' => $indikatorMutuId,
                    'keterangan' => $keterangan,
                    'status' => $status ? $status : 'Y',
                ]);
            }

            session()->setFlashdata('success', 'Data Indikator Mutu List Berhasil Di Update');
            return redirect()->to('/indikatormutulists');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $cekReferensi = $this->indikatormutulist->find($id);

            if ($cekReferensi) {
                // Menghapus foto jika ada
                if ($cekReferensi['gambar'] !== null) {
                    $fotoPath = FCPATH . 'indikatormutulist/' . $cekReferensi['gambar'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }

                // Menghapus data dari database
                $this->indikatormutulist->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
