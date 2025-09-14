<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Berita;
use App\Models\Dokter;
use App\Models\PageVisit;
use App\Models\Poli;
use App\Models\PollVote;
use App\Models\Spesialis;
use App\Models\User;
use App\Models\Video;

class HomeController extends BaseController
{
    protected $pagevisit;
    protected $berita;
    protected $dokter;
    protected $spesialis;
    protected $poliklinik;
    protected $video;
    protected $voting;
    protected $user;

    public function __construct()
    {
        $this->pagevisit = new PageVisit();
        $this->berita = new Berita();
        $this->dokter = new Dokter();
        $this->spesialis = new Spesialis();
        $this->poliklinik = new Poli();
        $this->video = new Video();
        $this->voting = new PollVote();
        $this->user = new User();
    }



    private function bulan_indonesia($bulan)
    {
        switch ($bulan) {
            case 1:
                return 'Januari';
            case 2:
                return 'Februari';
            case 3:
                return 'Maret';
            case 4:
                return 'April';
            case 5:
                return 'Mei';
            case 6:
                return 'Juni';
            case 7:
                return 'Juli';
            case 8:
                return 'Agustus';
            case 9:
                return 'September';
            case 10:
                return 'Oktober';
            case 11:
                return 'November';
            case 12:
                return 'Desember';
            default:
                return '';
        }
    }

    private function getRandomColor($opacity = 0.5)
    {
        // Palet warna yang lebih terang dan bervariasi
        $colors = [
            'rgba(2, 0, 113, 0.8)',    // Merah muda terang
            'rgba(45, 0, 128, 0.8)',     // Biru terang
            'rgba(255, 0, 0, 0.8)',     // Kuning terang
            'rgba(255, 255, 0, 0.8)',     // Hijau kebiruan terang
            'rgba(0, 255, 64, 1)',     // Oranye terang
            'rgba(22, 105, 67, 0.81)',    // Abu-abu terang
            'rgba(7, 179, 174, 0.81)',    // Merah jambu terang
            'rgba(58, 1, 105, 0.81)',      // Coklat terang
            'rgba(255, 0, 0, 0.5)',        // Merah terang
            'rgba(0, 0, 255, 0.5)',
            'rgba(1, 140, 131, 0.98)',
            'rgba(255, 140, 0, 0.98)',
            'rgba(255, 15, 0, 0.98)',
            'rgba(86, 5, 21, 0.79)',
        ];

        // Mengacak warna dari palet
        $randomIndex = array_rand($colors);
        return $colors[$randomIndex];
    }

    public function index(): string
    {
        // Ambil jumlah berita berdasarkan kategori
        $kategoriBerita = $this->berita
            ->select('kategori.title as kategori_title, COUNT(berita.kategori_id) as jumlah')
            ->join('kategori', 'kategori.idkategori = berita.kategori_id')
            ->groupBy('kategori.idkategori')
            ->findAll();

        // dd($kategoriBerita);
        // Kirim data ke view

        $dokter = $this->dokter->where('status', 'Y')->countAllResults();
        $spesialis = $this->spesialis->where('status', 'Y')->countAllResults();
        $poliklinik = $this->poliklinik->where('status', 'Y')->countAllResults();
        $video = $this->video->where('status', 'PB')->countAllResults();

        // Memanggil model untuk data pengunjung per bulan
        $monthlyVisits = $this->pagevisit->getMonthlyVisits();

        //voting per bulan 
        $monthlyVoting = $this->voting->getMonthlyVoting();

        $monthlyPosts = $this->berita->getGrafikBerita();

        $dataByCategory = [];
        $allMonths = [];

        foreach ($monthlyPosts as $post) {
            $bulan = $post['bulan'];
            $kategori = $post['kategori'];
            $jumlah = $post['jumlah'];

            if (!isset($dataByCategory[$kategori])) {
                $dataByCategory[$kategori] = [];
            }
            $dataByCategory[$kategori][$bulan] = $jumlah;

            if (!in_array($bulan, $allMonths)) {
                $allMonths[] = $bulan;
            }
        }

        sort($allMonths);

        // Mengubah angka bulan menjadi nama bulan dalam bahasa Indonesia
        $bulanIndo = array_map([$this, 'bulan_indonesia'], $allMonths);

        // Membuat struktur data untuk Chart.js
        $datasets = [];
        foreach ($dataByCategory as $kategori => $data) {
            $dataPoints = [];
            foreach ($allMonths as $month) {
                $dataPoints[] = isset($data[$month]) ? (int)$data[$month] : 0;
            }
            $datasets[] = [
                'label' => $kategori,
                'data' => $dataPoints,
                'borderColor' => $this->getRandomColor(),
                'backgroundColor' => $this->getRandomColor(0.2),
                'fill' => false
            ];
        }

        // $monthlyPosts = $this->berita->getGrafikBerita();

        // // dd($monthlyPosts);

        // $dataByCategory = [];
        // $allMonths = [];

        // foreach ($monthlyPosts as $post) {
        //     $bulan = $post['bulan'];
        //     $kategori = $post['kategori'];
        //     $jumlah = $post['jumlah'];

        //     if (!isset($dataByCategory[$kategori])) {
        //         $dataByCategory[$kategori] = [];
        //     }
        //     $dataByCategory[$kategori][$bulan] = $jumlah;

        //     if (!in_array($bulan, $allMonths)) {
        //         $allMonths[] = $bulan;
        //     }
        // }


        // sort($allMonths);

        // $datasets = [];
        // foreach ($dataByCategory as $kategori => $data) {
        //     $dataPoints = [];
        //     foreach ($allMonths as $month) {
        //         $dataPoints[] = isset($data[$month]) ? (int)$data[$month] : 0;
        //     }
        //     $datasets[] = [
        //         'label' => $kategori,
        //         'data' => $dataPoints,
        //         'borderColor' => $this->getRandomColor(),
        //         'backgroundColor' => $this->getRandomColor(0.2),
        //         'fill' => false
        //     ];
        // }

        // Debugging data
        // dd($dataByCategory, $allMonths, $datasets);



        $labelsVoting = [];
        $countsVoting = [];

        // Mengisi data labels dan counts untuk chart
        foreach ($monthlyVoting as $vote) {
            $bulan = $this->bulan_indonesia($vote['bulan']);
            $labelsVoting[] = $bulan;
            $countsVoting[] = $vote['total_votes'];
        }


        // Memanggil model untuk jumlah pengunjung hari ini
        $dailyVisitsToday = $this->pagevisit->getDailyVisitsToday();
        // dd($monthlyVisits);

        $labels = [];
        $counts = [];

        // Mengisi data labels dan counts untuk chart
        foreach ($monthlyVisits as $visit) {
            $bulan = $this->bulan_indonesia($visit['bulan']);
            $labels[] = $bulan;
            $counts[] = $visit['total_visits'];
        }

        $data = [
            'kategoriBerita' => $kategoriBerita,
            'dokter' => $dokter,
            'spesialis' => $spesialis,
            'poliklinik' => $poliklinik,
            'video' => $video,
            'labelsVisit' => $labels,
            'countVisit' => $counts,
            'monthlyVisits' => $monthlyVisits,
            'dailyVisitsToday' => $dailyVisitsToday,
            'labelsVoting' => $labelsVoting,
            'countsVoting' => $countsVoting,
            'labelsBerita' => $bulanIndo,
            'datasetsBerita' => $datasets,
        ];


        return view('backend/main/home', $data);
    }


    public function myAccount()
    {
        $data = [
            'user' => $this->user->where('iduser', session()->get('idUser'))->first(),
            'title' => 'My Account'
        ];
        // dd($data);

        return view('backend/main/myaccount', $data);
    }

    public function saveAccount()
    {
        $nama = $this->request->getPost('nama');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $confirmpassword = $this->request->getPost('confirmpassword');


        // Persiapkan data dasar
        $data = [
            'nama' => $nama,
            'username' => $username,
        ];

        // Jika password dan konfirmasi password diisi, lakukan validasi
        if (!empty($password) || !empty($confirmpassword)) {
            // Validasi kata sandi
            if (!$this->isValidPassword($password)) {
                session()->setFlashdata('error_password', 'Password tidak valid. Pastikan panjang minimal 8 karakter, terdiri dari huruf besar, huruf kecil, simbol, dan angka.');
                return redirect()->back()->withInput();
            }

            if ($password !== $confirmpassword) {
                session()->setFlashdata('error_confirmpassword', 'Password dengan Konfirmasi Password Tidak cocok.');
                return redirect()->back()->withInput();
            }

            // Jika validasi berhasil, tambahkan password yang di-hash ke data
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Periksa apakah ada ID
        $id = $this->request->getPost('id');

        if ($id) {
            // Jika ID ada, update data
            $this->user->update($id, $data);
        } else {
            // Jika ID tidak ada, buat data baru
            $this->user->insert($data);
        }

        session()->setFlashdata('success', 'Data Berhasil Di Update');
        return redirect()->back();
    }

    private function isValidPassword($password)
    {
        // Panjang minimal 8 karakter
        if (strlen($password) < 8) {
            return false;
        }

        // Kombinasi huruf besar, huruf kecil, simbol, dan angka
        if (
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W_]/', $password)
        ) {
            return false;
        }

        // // Tidak ada perulangan karakter
        // if (preg_match('/(.).*\1/', $password)) {
        //     return false;
        // }

        return true;
    }
}
