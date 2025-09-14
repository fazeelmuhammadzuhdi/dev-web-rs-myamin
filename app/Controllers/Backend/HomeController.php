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

/**
 * HomeController handles backend dashboard functionality
 * 
 * This controller manages dashboard statistics, charts, and user account management
 * with comprehensive data visualization and analytics.
 */
class HomeController extends BaseController
{
    // Model instances
    private PageVisit $pageVisitModel;
    private Berita $beritaModel;
    private Dokter $dokterModel;
    private Spesialis $spesialisModel;
    private Poli $poliModel;
    private Video $videoModel;
    private PollVote $pollVoteModel;
    private User $userModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->pageVisitModel = new PageVisit();
        $this->beritaModel = new Berita();
        $this->dokterModel = new Dokter();
        $this->spesialisModel = new Spesialis();
        $this->poliModel = new Poli();
        $this->videoModel = new Video();
        $this->pollVoteModel = new PollVote();
        $this->userModel = new User();
    }

    /**
     * Convert month number to Indonesian month name
     */
    private function bulan_indonesia(int $bulan): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $months[$bulan] ?? '';
    }

    /**
     * Get random color for charts
     */
    private function getRandomColor(float $opacity = 0.5): string
    {
        $colors = [
            'rgba(2, 0, 113, ' . $opacity . ')',
            'rgba(45, 0, 128, ' . $opacity . ')',
            'rgba(255, 0, 0, ' . $opacity . ')',
            'rgba(255, 255, 0, ' . $opacity . ')',
            'rgba(0, 255, 64, ' . $opacity . ')',
            'rgba(22, 105, 67, ' . $opacity . ')',
            'rgba(7, 179, 174, ' . $opacity . ')',
            'rgba(58, 1, 105, ' . $opacity . ')',
            'rgba(255, 0, 0, ' . $opacity . ')',
            'rgba(0, 0, 255, ' . $opacity . ')',
            'rgba(1, 140, 131, ' . $opacity . ')',
            'rgba(255, 140, 0, ' . $opacity . ')',
            'rgba(255, 15, 0, ' . $opacity . ')',
            'rgba(86, 5, 21, ' . $opacity . ')',
        ];

        return $colors[array_rand($colors)];
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats(): array
    {
        return [
            'dokter' => $this->dokterModel->where('status', 'Y')->countAllResults(),
            'spesialis' => $this->spesialisModel->where('status', 'Y')->countAllResults(),
            'poliklinik' => $this->poliModel->where('status', 'Y')->countAllResults(),
            'video' => $this->videoModel->where('status', 'PB')->countAllResults(),
        ];
    }

    /**
     * Get news data by category
     */
    private function getNewsByCategory(): array
    {
        return $this->beritaModel
            ->select('kategori.title as kategori_title, COUNT(berita.kategori_id) as jumlah')
            ->join('kategori', 'kategori.idkategori = berita.kategori_id')
            ->groupBy('kategori.idkategori')
            ->findAll();
    }

    /**
     * Get monthly visits data
     */
    private function getMonthlyVisitsData(): array
    {
        $monthlyVisits = $this->pageVisitModel->getMonthlyVisits();
        
        $labels = [];
        $counts = [];

        foreach ($monthlyVisits as $visit) {
            $labels[] = $this->bulan_indonesia($visit['bulan']);
            $counts[] = $visit['total_visits'];
        }

        return [
            'labels' => $labels,
            'counts' => $counts,
            'rawData' => $monthlyVisits
        ];
    }

    /**
     * Get monthly voting data
     */
    private function getMonthlyVotingData(): array
    {
        $monthlyVoting = $this->pollVoteModel->getMonthlyVoting();
        
        $labels = [];
        $counts = [];

        foreach ($monthlyVoting as $vote) {
            $labels[] = $this->bulan_indonesia($vote['bulan']);
            $counts[] = $vote['total_votes'];
        }

        return [
            'labels' => $labels,
            'counts' => $counts
        ];
    }

    /**
     * Get news chart data
     */
    private function getNewsChartData(): array
    {
        $monthlyPosts = $this->beritaModel->getGrafikBerita();
        
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

        // Convert month numbers to Indonesian month names
        $bulanIndo = array_map([$this, 'bulan_indonesia'], $allMonths);

        // Create datasets for Chart.js
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

        return [
            'labels' => $bulanIndo,
            'datasets' => $datasets
        ];
    }

    /**
     * Display dashboard
     */
    public function index(): string
    {
        $stats = $this->getDashboardStats();
        $kategoriBerita = $this->getNewsByCategory();
        $visitsData = $this->getMonthlyVisitsData();
        $votingData = $this->getMonthlyVotingData();
        $newsData = $this->getNewsChartData();

        $data = [
            'kategoriBerita' => $kategoriBerita,
            'dokter' => $stats['dokter'],
            'spesialis' => $stats['spesialis'],
            'poliklinik' => $stats['poliklinik'],
            'video' => $stats['video'],
            'labelsVisit' => $visitsData['labels'],
            'countVisit' => $visitsData['counts'],
            'monthlyVisits' => $visitsData['rawData'],
            'dailyVisitsToday' => $this->pageVisitModel->getDailyVisitsToday(),
            'labelsVoting' => $votingData['labels'],
            'countsVoting' => $votingData['counts'],
            'labelsBerita' => $newsData['labels'],
            'datasetsBerita' => $newsData['datasets'],
        ];

        return view('backend/main/home', $data);
    }

    /**
     * Display user account page
     */
    public function myAccount(): string
    {
        $data = [
            'user' => $this->userModel->where('iduser', session()->get('idUser'))->first(),
            'title' => 'My Account'
        ];

        return view('backend/main/myaccount', $data);
    }

    /**
     * Get account validation rules
     */
    private function getAccountValidationRules(): array
    {
        return [
            'nama' => [
                'label' => 'Nama',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'min_length' => 'Username minimal 3 karakter',
                    'max_length' => 'Username maksimal 50 karakter'
                ]
            ]
        ];
    }

    /**
     * Validate password strength
     */
    private function isValidPassword(string $password): bool
    {
        // Minimum 8 characters
        if (strlen($password) < 8) {
            return false;
        }

        // Must contain uppercase, lowercase, number, and special character
        return preg_match('/[A-Z]/', $password) &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[0-9]/', $password) &&
               preg_match('/[\W_]/', $password);
    }

    /**
     * Save user account
     */
    public function saveAccount()
    {
        $data = $this->getFormData(['id', 'nama', 'username', 'password', 'confirmpassword']);

        $rules = $this->getAccountValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'username']);
        }

        $accountData = [
            'nama' => $data['nama'],
            'username' => $data['username'],
        ];

        // Handle password update if provided
        if (!empty($data['password']) || !empty($data['confirmpassword'])) {
            if (!$this->isValidPassword($data['password'])) {
                session()->setFlashdata('error_password', 'Password tidak valid. Pastikan panjang minimal 8 karakter, terdiri dari huruf besar, huruf kecil, simbol, dan angka.');
                return redirect()->back()->withInput();
            }

            if ($data['password'] !== $data['confirmpassword']) {
                session()->setFlashdata('error_confirmpassword', 'Password dengan Konfirmasi Password Tidak cocok.');
                return redirect()->back()->withInput();
            }

            $accountData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Save or update account
        if ($data['id']) {
            $this->userModel->update($data['id'], $accountData);
        } else {
            $this->userModel->insert($accountData);
        }

        return $this->setSuccessMessage('Data Berhasil Di Update');
    }
}