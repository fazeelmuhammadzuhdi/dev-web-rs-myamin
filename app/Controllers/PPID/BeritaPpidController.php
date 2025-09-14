<?php

namespace App\Controllers\PPID;

use App\Models\BeritaPPID;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use App\Models\KategoriInformasiPPID;

class BeritaPpidController extends BaseController
{
    protected $beritappid;
    protected $kategori;

    public function __construct()
    {
        $this->beritappid = new BeritaPPID();
        $this->kategori = new KategoriInformasiPPID();
        // slug
        helper('slug');
    }

    public function index($tipe = 'ppid')
    {
        $data['title'] = ($tipe === 'ppid') ? 'Berita PPID' : 'Informasi PKRS';
        $data['tipe'] = $tipe;

        return view('backend/beritappid/index', $data);
    }


    public function create($tipe = 'ppid')
    {

        $kategori =  [];

        if ($tipe === 'ppid') {
            $kategori = BeritaPPID::KATEGORI_PPID;
        } else {
            $kategori = BeritaPPID::KATEGORI_PKRS;
        }

        $data['kategori'] = $this->kategori->where('status', 'Y')->whereIn('idkategori', $kategori)->findAll();
        $data['tipe'] = $tipe;


        return view('backend/beritappid/create', $data);
    }

    public function getData($tipe = 'ppid')
    {
        if ($this->request->isAJAX()) {

            $kategori =  [];

            if ($tipe === 'ppid') {
                $kategori = BeritaPPID::KATEGORI_PPID;
            } else {
                $kategori = BeritaPPID::KATEGORI_PKRS;
            }


            $builder = $this->beritappid->getBerita($kategori);


            return DataTable::of($builder)

                ->edit('status', function ($row) {
                    if ($row->status == 'Y') {
                        return '<span class="badge badge-success">Publish</span>';
                    } else {
                        return '<span class="badge badge-danger">Belum Publish</span>';
                    }
                })
                ->edit('link', function ($row) {
                    if ($row->link !== null) {
                        return '<a href="' . $row->link . '" target="_blank">' . $row->link . '</a>';
                    } else {
                        return '';
                    }
                })
                ->edit('tanggal', function ($row) {
                    return tanggal_indonesia($row->tanggal);
                })


                ->add('action', function ($row) use ($tipe) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" judul="Hapus Data" onclick="hapus(\'' . $row->idberita . '\',\'' . $row->judul . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" judul="Edit Data" onclick="edit(\'' . $row->idberita . '\',\'' . $tipe . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save($tipe = 'ppid')
    {
        $kategori_id = $this->request->getVar('kategori_id');
        $judul = $this->request->getVar('judul');
        $tanggal = $this->request->getVar('tanggal');
        $tahun = $this->request->getVar('tahun');
        $jangka = $this->request->getVar('jangka');
        $penanggungJawab = $this->request->getVar('penanggung_jawab');
        $tempat = $this->request->getVar('tempat');
        $link = $this->request->getVar('link');
        $filetype = $this->request->getVar('filetype');
        $konten = $this->request->getVar('konten');
        $status = $this->request->getVar('status');
        $userId = session()->get('idUser');

        $rules = $this->validate([
            'judul' => [
                'label' => 'Judul Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'tahun' => [
                'label' => 'tahun Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'jangka' => [
                'label' => 'Jangka Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'penanggung_jawab' => [
                'label' => 'Penanggung Jawab Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'tempat' => [
                'label' => 'Tempat Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'link' => [
                'label' => 'Link Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'filetype' => [
                'label' => 'Filetype Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'tanggal' => [
                'label' => 'Tanggal Berita PPID',
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

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_judul' => $validation->getError('judul'),
                'error_tanggal' => $validation->getError('tanggal'),
                'error_konten' => $validation->getError('konten'),
                'error_tahun' => $validation->getError('tahun'),
                'error_jangka' => $validation->getError('jangka'),
                'error_penanggung_jawab' => $validation->getError('penanggung_jawab'),
                'error_tempat' => $validation->getError('tempat'),
                'error_link' => $validation->getError('link'),
                'error_filetype' => $validation->getError('filetype'),
                'error_kategori_id' => $validation->getError('kategori_id'),
                'error_status' => $validation->getError('status'),
            ]);
            return redirect()->back()->withInput();
        } else {


            $this->beritappid->insert([
                'judul' => $judul,
                'user_id' => $userId,
                'tanggal' => $tanggal,
                'konten' => $konten,
                'kategori_id' => $kategori_id,
                'status' => $status,
                'tahun' => $tahun,
                'jangka' => $jangka,
                'penanggung_jawab' => $penanggungJawab,
                'tempat' => $tempat,
                'link' => $link,
                'filetype' => $filetype,
                'slug' => createSlug($judul),
                'nm_status' => 'Publish',
            ]);

            session()->setFlashdata('success', 'Data Berita PPID Berhasil Ditambahkan');
            return redirect()->to('/beritappid/' . $tipe);
        }
    }



    public function edit($id = null, $tipe = 'ppid')
    {
        $data['beritappid'] = $this->beritappid->find($id);

        $kategori =  [];

        if ($tipe === 'ppid') {
            $kategori = BeritaPPID::KATEGORI_PPID;
        } else {
            $kategori = BeritaPPID::KATEGORI_PKRS;
        }

        $data['kategori'] = $this->kategori->where('status', 'Y')->whereIn('idkategori', $kategori)->findAll();
        $data['tipe'] = $tipe;
        return view('backend/beritappid/edit', $data);
    }

    public function update()
    {
        $tipe = $this->request->getVar('tipe');
        $idBerita = $this->request->getVar('idberita');
        $kategori_id = $this->request->getVar('kategori_id');
        $judul = $this->request->getVar('judul');
        $tanggal = $this->request->getVar('tanggal');
        $tahun = $this->request->getVar('tahun');
        $jangka = $this->request->getVar('jangka');
        $penanggungJawab = $this->request->getVar('penanggung_jawab');
        $tempat = $this->request->getVar('tempat');
        $link = $this->request->getVar('link');
        $filetype = $this->request->getVar('filetype');
        $konten = $this->request->getVar('konten');
        $status = $this->request->getVar('status');
        $userId = session()->get('idUser');


        $rules = $this->validate([
            'judul' => [
                'label' => 'Judul Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'tahun' => [
                'label' => 'tahun Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'jangka' => [
                'label' => 'Jangka Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'penanggung_jawab' => [
                'label' => 'Penanggung Jawab Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'tempat' => [
                'label' => 'Tempat Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'link' => [
                'label' => 'Link Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'filetype' => [
                'label' => 'Filetype Berita PPID',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'tanggal' => [
                'label' => 'Tanggal Berita PPID',
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

        ]);


        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_judul' => $validation->getError('judul'),
                'error_tanggal' => $validation->getError('tanggal'),
                'error_konten' => $validation->getError('konten'),
                'error_tahun' => $validation->getError('tahun'),
                'error_jangka' => $validation->getError('jangka'),
                'error_penanggung_jawab' => $validation->getError('penanggung_jawab'),
                'error_tempat' => $validation->getError('tempat'),
                'error_link' => $validation->getError('link'),
                'error_filetype' => $validation->getError('filetype'),
                'error_kategori_id' => $validation->getError('kategori_id'),
                'error_status' => $validation->getError('status'),
            ]);
            return redirect()->back()->withInput();
        } else {


            $this->beritappid->update($idBerita, [
                'judul' => $judul,
                'user_id' => $userId,
                'tanggal' => $tanggal,
                'konten' => $konten,
                'kategori_id' => $kategori_id,
                'status' => $status,
                'tahun' => $tahun,
                'jangka' => $jangka,
                'penanggung_jawab' => $penanggungJawab,
                'tempat' => $tempat,
                'link' => $link,
                'filetype' => $filetype,
                'slug' => createSlug($judul),
                'nm_status' => 'Publish',
            ]);

            session()->setFlashdata('success', 'Data Berita PPID Berhasil Di Update');
            return redirect()->to('/beritappid/' . $tipe);
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $cekReferensi = $this->beritappid->find($id);

            if ($cekReferensi) {

                // Menghapus data dari database
                $this->beritappid->delete($id);


                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }

    public function apiIndex()
    {
        $data['title'] = 'List Berita PPID API';
        $data['beritaPpid'] = $this->getDataApi();
        $data['existingData'] = $this->beritappid->getExistingData();
        // Buat array untuk memudahkan pengecekan
        $existingIds = array_column($data['existingData'], 'idberita');
        $data['existingIds'] = $existingIds;

        return view('backend/beritappid/dataapippid', $data);
    }

    public function getDataApi()
    {
        $apiUrl = 'https://ppid.sumbarprov.go.id/api/cluster-data?id_instansi=99';

        // Ambil data dari API menggunakan cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Ubah respons dari JSON ke array
        $data = json_decode($response, true);


        return $data;
    }

    public function cetakLaporanInformasiPublik()
    {
        $data['title'] = 'Laporan Daftar Informasi Publik RSUD. Prof. H. Muhammad. Yamin, SH';

        // Get the start and end year from the request
        $startYear = $this->request->getGet('startYear');
        $endYear = $this->request->getGet('endYear');

        // Check if both startYear and endYear are provided, then modify the title
        if ($startYear && $endYear) {
            $data['title'] = 'Laporan Daftar Informasi Publik RSUD. Prof. H. Muhammad. Yamin, SH Tahun ' . $startYear . ' Hingga ' . $endYear;
        }


        // Initialize query builder to fetch all data
        $builder = $this->beritappid->builder();

        // Filter kategori hanya untuk PPID
        $builder->whereIn('kategori_id', BeritaPPID::KATEGORI_PPID);
        // Apply filtering if startYear or endYear are provided
        if ($startYear) {
            $builder->where('tahun >=', $startYear);
        }
        if ($endYear) {
            $builder->where('tahun <=', $endYear);
        }
        // Order by 'tanggal' in descending order (latest first)
        $builder->orderBy('tanggal', 'DESC');
        // Execute the query and fetch the filtered data
        $data['beritaPpid'] = $builder->get()->getResultArray();

        // dd($data['beritaPpid']);


        $data['total'] = count($data['beritaPpid']);

        // Convert images to Base64 for the report
        $logoPemrov = base64_encode(file_get_contents(FCPATH . 'assets/pemprov.jpg'));
        $data['srcLogoPemrov'] = 'data:image/png;base64,' . $logoPemrov;

        $logoRsud = base64_encode(file_get_contents(FCPATH . 'assets/logo.png'));
        $data['srcLogoRsud'] = 'data:image/png;base64,' . $logoRsud;
        $data['startYear'] = $startYear;
        $data['endYear'] = $endYear;

        return view('backend/beritappid/laporan-dip', $data);
    }


    public function saveSelectedData()
    {
        $selectedData = $this->request->getPost('pilih');

        if (!empty($selectedData)) {
            foreach ($selectedData as $beritaJson) {
                $berita = json_decode($beritaJson, true);

                $existingData = $this->beritappid->where('idberita', $berita['id_content'])->first();
                if ($existingData) {
                    return redirect()->to('/apiberitappid')->with('error', 'Data dengan ID Content ' . $berita['id_content'] . ' sudah ada di database.');
                }


                // Siapkan data untuk dimasukkan ke dalam database
                $data = [
                    'idberita' => $berita['id_content'],
                    'judul' => $berita['title_content'],
                    'user_id' => $berita['id_user'],
                    'tanggal' => $berita['created'],
                    'konten' => $berita['title_sub_category'],
                    'kategori_id' => $berita['id_category'],
                    'tahun' => $berita['tahun'],
                    'jangka' => $berita['jangka_waktu'],
                    'tempat' => $berita['tgl_dan_tempat'],
                    'penanggung_jawab' => $berita['penanggung_jawab'],
                    'filetype' => 'pdf.png',
                    'link' => $berita['downloads'],
                    'download' => $berita['hits'],
                    'slug' => createSlug($berita['title_content']), // Isi sesuai kebutuhan
                    'nm_status' => $berita['nm_status'],
                ];

                // Masukkan data ke dalam database
                $this->beritappid->insert($data);
            }

            return redirect()->to('/apiberitappid')->with('success', 'Data berhasil disimpan.');
        }

        return redirect()->to('/apiberitappid')->with('error', 'Tidak ada data yang dipilih.');
    }
}
