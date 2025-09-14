<?php

namespace App\Controllers\PPID;

use App\Controllers\BaseController;
use App\Models\Inovasi;
use CodeIgniter\HTTP\ResponseInterface;
use Hermawan\DataTables\DataTable;

class InovasiController extends BaseController
{
    protected $inovasi;

    public function __construct()
    {
        $this->inovasi = new Inovasi();
    }

    public function index()
    {
        $data['title'] = 'List Inovasi';

        return view('backend/inovasi/index', $data);
    }

    public function create()
    {
        return view('backend/inovasi/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {

            $builder = $this->inovasi->select('idinovasi,judul,tahun,jenis');


            return DataTable::of($builder)

                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idinovasi . '\',\'' . $row->judul . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idinovasi . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $judul = $this->request->getVar('judul');
        $tahun = $this->request->getVar('tahun');
        $jenis = $this->request->getVar('jenis');
        $tujuan = $this->request->getVar('tujuan');
        $manfaat = $this->request->getVar('manfaat');
        $rancang = $this->request->getVar('rancang');
        $tahapan = $this->request->getVar('tahapan');
        $digital = $this->request->getVar('digital');
        $inisiator = $this->request->getVar('inisiator');
        $hasil = $this->request->getVar('hasil');
        $ujicoba = $this->request->getVar('ujicoba');
        $implementasi = $this->request->getVar('implementasi');
        $urusan = $this->request->getVar('urusan');
        $panduan_teknis = $this->request->getVar('panduan_teknis');
        $link_youtube = $this->request->getVar('link_youtube');

        $rules = $this->validate([
            'judul' => [
                'label' => 'Judul Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'tahun' => [
                'label' => 'Tahu Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'jenis' => [
                'label' => 'Jenis Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'tujuan' => [
                'label' => 'Tujuan Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'manfaat' => [
                'label' => 'Manfaat Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'rancang' => [
                'label' => 'Rancang Bangun Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'tahapan' => [
                'label' => 'Tahapan Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'digital' => [
                'label' => 'Digital Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'inisiator' => [
                'label' => 'Inisiator Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'hasil' => [
                'label' => 'Hasil Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'ujicoba' => [
                'label' => 'Uji Coba Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'implementasi' => [
                'label' => 'Implementasi Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_judul' => $validation->getError('judul'),
                'error_tahun' => $validation->getError('tahun'),
                'error_jenis' => $validation->getError('jenis'),
                'error_tujuan' => $validation->getError('tujuan'),
                'error_manfaat' => $validation->getError('manfaat'),
                'error_rancang' => $validation->getError('rancang'),
                'error_tahapan' => $validation->getError('tahapan'),
                'error_digital' => $validation->getError('digital'),
                'error_inisiator' => $validation->getError('inisiator'),
                'error_hasil' => $validation->getError('hasil'),
                'error_ujicoba' => $validation->getError('ujicoba'),
                'error_implementasi' => $validation->getError('implementasi'),
            ]);
            return redirect()->back()->withInput();
        } else {

            $this->inovasi->insert([
                'judul' => $judul,
                'tahun' => $tahun,
                'jenis' => $jenis,
                'tujuan' => $tujuan,
                'manfaat' => $manfaat,
                'rancang' => $rancang,
                'tahapan' => $tahapan,
                'digital' => $digital,
                'inisiator' => $inisiator,
                'hasil' => $hasil,
                'ujicoba' => $ujicoba,
                'implementasi' => $implementasi,
                'urusan' => $urusan,
                'panduan_teknis' => $panduan_teknis,
                'link_youtube' => $link_youtube,
            ]);

            session()->setFlashdata('success', 'Data Inovasi Berhasil Di Tambahkan');
            return redirect()->to('/inovasis');
        }
    }

    public function edit($id = null)
    {
        $data['inovasi'] = $this->inovasi->find($id);
        return view('backend/inovasi/edit', $data);
    }

    public function update()
    {
        $idinovasi = $this->request->getVar('idinovasi');
        $judul = $this->request->getVar('judul');
        $tahun = $this->request->getVar('tahun');
        $jenis = $this->request->getVar('jenis');
        $tujuan = $this->request->getVar('tujuan');
        $manfaat = $this->request->getVar('manfaat');
        $rancang = $this->request->getVar('rancang');
        $tahapan = $this->request->getVar('tahapan');
        $digital = $this->request->getVar('digital');
        $inisiator = $this->request->getVar('inisiator');
        $hasil = $this->request->getVar('hasil');
        $ujicoba = $this->request->getVar('ujicoba');
        $implementasi = $this->request->getVar('implementasi');
        $urusan = $this->request->getVar('urusan');
        $panduan_teknis = $this->request->getVar('panduan_teknis');
        $link_youtube = $this->request->getVar('link_youtube');

        $rules = [
            'judul' => [
                'label' => 'Judul Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],

            'tahun' => [
                'label' => 'Tahu Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'jenis' => [
                'label' => 'Jenis Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'tujuan' => [
                'label' => 'Tujuan Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'manfaat' => [
                'label' => 'Manfaat Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'rancang' => [
                'label' => 'Rancang Bangun Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'tahapan' => [
                'label' => 'Tahapan Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'digital' => [
                'label' => 'Digital Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'inisiator' => [
                'label' => 'Inisiator Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'hasil' => [
                'label' => 'Hasil Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'ujicoba' => [
                'label' => 'Uji Coba Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],
            'implementasi' => [
                'label' => 'Implementasi Inovasi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong ',
                ]
            ],

        ];


        $validation = \Config\Services::validation();
        $isValid = $validation->withRequest($this->request)->setRules($rules)->run();

        if (!$isValid) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_judul' => $validation->getError('judul'),
                'error_tahun' => $validation->getError('tahun'),
                'error_jenis' => $validation->getError('jenis'),
                'error_tujuan' => $validation->getError('tujuan'),
                'error_manfaat' => $validation->getError('manfaat'),
                'error_rancang' => $validation->getError('rancang'),
                'error_tahapan' => $validation->getError('tahapan'),
                'error_digital' => $validation->getError('digital'),
                'error_inisiator' => $validation->getError('inisiator'),
                'error_hasil' => $validation->getError('hasil'),
                'error_ujicoba' => $validation->getError('ujicoba'),
                'error_implementasi' => $validation->getError('implementasi'),
            ]);

            return redirect()->back()->withInput();
        } else {

            // Jika tidak ada foto baru diunggah, update data manajemenprofil tanpa foto
            $this->inovasi->update($idinovasi, [
                'judul' => $judul,
                'tahun' => $tahun,
                'jenis' => $jenis,
                'tujuan' => $tujuan,
                'manfaat' => $manfaat,
                'rancang' => $rancang,
                'tahapan' => $tahapan,
                'digital' => $digital,
                'inisiator' => $inisiator,
                'hasil' => $hasil,
                'ujicoba' => $ujicoba,
                'implementasi' => $implementasi,
                'urusan' => $urusan,
                'panduan_teknis' => $panduan_teknis,
                'link_youtube' => $link_youtube,
            ]);

            session()->setFlashdata('success', 'Data Inovasi Berhasil Di Update');
            return redirect()->to('/inovasis');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $sj = $this->inovasi->find($id);

            if ($sj) {
                $this->inovasi->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
