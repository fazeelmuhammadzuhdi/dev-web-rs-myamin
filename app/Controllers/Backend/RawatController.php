<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\Rawat;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RawatController extends BaseController
{
    protected $rawat;

    public function __construct()
    {
        $this->rawat = new Rawat();

        helper('string');
        helper('slug');
    }

    public function index()
    {

        $data['title'] = 'Rawat';

        return view('backend/rawat/index', $data);
    }

    public function create()
    {
        return view('backend/rawat/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->rawat->select('idrawat,nama,keterangan,status')->orderBy('nama', 'ASC');
            return DataTable::of($builder)
                ->edit('status', function ($row) {
                    if ($row->status == 'Y') {
                        return '<span class="badge badge-success">Aktif</span>';
                    } else {
                        return '<span class="badge badge-warning">Tidak Aktif</span>';
                    }
                })
                ->edit('keterangan', function ($row) {
                    if ($row->keterangan) {
                        $doc = new DOMDocument();
                        @$doc->loadHTML($row->keterangan);
                        $text =  $doc->textContent;

                        $text =  strip_empty_p_tags(limit_words($text, 40));


                        return $text;
                    }
                    return '-';
                })

                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idrawat . '\',\'' . $row->keterangan . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idrawat . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }

    private function savesBase64Images($konten)
    {
        // Temukan semua gambar base64 dalam konten
        preg_match_all('/data:image\/(\w+);base64,([^"]+)/', $konten, $matches);

        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $base64Image) {
                // Extract base64 data
                list($type, $data) = explode(';', $base64Image);
                list(, $data) = explode(',', $data);

                // Decode base64 data
                $data = base64_decode($data);

                // Buat nama file acak
                $fileName = uniqid() . '.jpg';

                // Path penyimpanan
                $filePath = FCPATH . 'uploadGaleri/' . $fileName;

                // Simpan gambar ke server
                if (file_put_contents($filePath, $data)) {
                    // Ganti konten base64 dengan URL gambar yang disimpan
                    $konten = str_replace($base64Image, base_url('public/uploadGaleri/' . $fileName), $konten);
                } else {
                    // error message
                    session()->setFlashdata('error', 'Data Error');
                }
            }
        }

        return $konten;
    }



    public function save()
    {
        $keterangan = $this->request->getVar('keterangan');
        $nama = $this->request->getVar('nama');
        $status = $this->request->getVar('status');

        $rules = $this->validate([
            'keterangan' => [
                'label' => 'Rawat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'nama' => [
                'label' => 'Nama Rawat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_keterangan' => $validation->getError('keterangan'),
                'error_nama' => $validation->getError('nama'),
            ]);
            return redirect()->back()->withInput();
        } else {

            $keterangan = $this->savesBase64Images($keterangan);


            $this->rawat->insert([
                'keterangan' => $keterangan,
                'nama' => $nama,
                'status' => $status ? $status : 'Y',
                'slug' => createSlug($nama),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data Rawat Berhasil Di Tambahkan');
            return redirect()->to('/rawat');
        }
    }

    public function edit($id = null)
    {
        $data['rawat'] = $this->rawat->find($id);
        return view('backend/rawat/edit', $data);
    }

    public function update()
    {

        $idRawat = $this->request->getVar('idrawat');
        $keterangan = $this->request->getVar('keterangan');
        $nama = $this->request->getVar('nama');
        $status = $this->request->getVar('status');

        $rules = $this->validate([
            'keterangan' => [
                'label' => 'Keterangan Rawat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'nama' => [
                'label' => 'Nama Rawat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_keterangan' => $validation->getError('keterangan'),
                'error_nama' => $validation->getError('nama'),
            ]);
            return redirect()->back()->withInput();
        } else {

            $keterangan = $this->savesBase64Images($keterangan);


            $data = [
                'keterangan' => $keterangan,
                'nama' => $nama,
                'slug' => createSlug($nama),

                'status' => $status ? $status : 'Y',
            ];

            $this->rawat->update($idRawat, $data);

            session()->setFlashdata('success', 'Data Rawat Berhasil Di Update');
            return redirect()->to('/rawat');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $keterangan = $this->rawat->find($id);

            if ($keterangan) {
                $this->rawat->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
