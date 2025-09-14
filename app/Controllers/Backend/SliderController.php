<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\Slider;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class SliderController extends BaseController
{
    protected $slider;

    public function __construct()
    {
        $this->slider = new Slider();
    }

    public function index()
    {
        $data['title'] = 'Slider';
        return view('backend/slider/index', $data);
    }

    public function create()
    {
        return view('backend/slider/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->slider->select('idslider,judul,keterangan,gambar');


            return DataTable::of($builder)
                ->edit('gambar', function ($row) {
                    if ($row->gambar !== null) {
                        $imageUrl = base_url('slider/' . $row->gambar);
                        return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" width="160" height="100"></a>';
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

                    <button type="button" class="btn btn-round btn-danger mx-1" judul="Hapus Data" onclick="hapus(\'' . $row->idslider . '\',\'' . $row->judul . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" judul="Edit Data" onclick="edit(\'' . $row->idslider . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $judul = $this->request->getVar('judul');
        $keterangan = $this->request->getVar('keterangan');

        $rules = $this->validate([

            'judul' => [
                'label' => 'Judul Slider',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Slider',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'gambar' => [
                'label' => 'Gambar Slider',
                'rules' => 'uploaded[gambar]|max_size[gambar,1024]|mime_in[gambar,image/jpeg,image/png,image/jpg,image/webp]',
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
                'error_keterangan' => $validation->getError('keterangan'),
                'error_gambar' => $validation->getError('gambar'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $fileFoto = $this->request->getFile('gambar');

            $namaFoto = "Slider" . '_' . $fileFoto->getRandomName();
            // Pindahkan file foto ke folder tujuan (public/slider)
            // $fileFoto->move(FCPATH . 'slider', $namaFoto);
            $fileFoto->move(FCPATH . 'slider', $namaFoto);

            $this->slider->insert([
                'judul' => $judul,
                'keterangan' => $keterangan,
                'gambar' => $namaFoto,
                'thumbnail' => $namaFoto,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data Slider Berhasil Di Tambahkan');
            return redirect()->to('/sliders');
        }
    }

    public function edit($id = null)
    {
        $data['sliders'] = $this->slider->find($id);
        return view('backend/slider/edit', $data);
    }

    public function update()
    {

        $idSlider = $this->request->getVar('idslider');
        $judul = $this->request->getVar('judul');
        $keterangan = $this->request->getVar('keterangan');
        $gambar = $this->request->getFile('gambar');


        $rules = [

            'judul' => [
                'label' => 'Judul Slider',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Slider',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
        ];

        if ($gambar->isValid() && !$gambar->hasMoved()) {
            // Validasi gambar
            $rules['gambar'] = 'uploaded[gambar]|mime_in[gambar,image/jpeg,image/png,image/webp]|max_size[gambar,1024]';
        }

        $validation = \Config\Services::validation();
        $isValid = $validation->withRequest($this->request)->setRules($rules)->run();

        if (!$isValid) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_judul' => $validation->getError('judul'),
                'error_keterangan' => $validation->getError('keterangan'),
            ]);

            return redirect()->back()->withInput();
        } else {

            // Menghapus foto lama jika ada foto baru diunggah
            if ($gambar->isValid() && !$gambar->hasMoved()) {
                $slider = $this->slider->find($idSlider);
                if ($slider['gambar'] !== null) {
                    $oldFotoPath = FCPATH . 'slider/' . $slider['gambar'];
                    if (file_exists($oldFotoPath)) {
                        unlink($oldFotoPath);
                    }
                }

                $newFotoName = "Slider" . '_' . $gambar->getRandomName();
                $gambar->move(FCPATH . 'slider', $newFotoName);

                // Update data slider dengan foto baru
                $this->slider->update($idSlider, [
                    'judul' => $judul,
                    'keterangan' => $keterangan,
                    'gambar' => $newFotoName,
                    'thumbnail' => $newFotoName,
                ]);
            } else {
                // Jika tidak ada foto baru diunggah, update data slider tanpa foto
                $this->slider->update($idSlider, [
                    'judul' => $judul,
                    'keterangan' => $keterangan,
                ]);
            }

            session()->setFlashdata('success', 'Data Slider Berhasil Di Update');
            return redirect()->to('/sliders');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $cekReferensi = $this->slider->find($id);

            if ($cekReferensi) {
                // Menghapus foto jika ada
                if ($cekReferensi['gambar'] !== null) {
                    $fotoPath = FCPATH . 'slider/' . $cekReferensi['gambar'];
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }
                }

                // Menghapus data dari database
                $this->slider->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
