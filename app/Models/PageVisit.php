<?php

namespace App\Models;

use CodeIgniter\Model;

class PageVisit extends Model
{
    protected $table = 'page_visits';
    protected $primaryKey = 'idpagevisit';
    protected $allowedFields = ['idpagevisit', 'tanggal', 'ip_address', 'user_agent', 'count'];

    public function incrementCount($tanggal, $ipAddress, $userAgent)
    {
        $existing = $this->where('tanggal', $tanggal)
            ->where('ip_address', $ipAddress)
            ->where('user_agent', $userAgent)
            ->first();
        if ($existing) {
            $this->update($existing['idpagevisit'], ['count' => $existing['count'] + 1]);
        } else {
            $this->insert(['tanggal' => $tanggal, 'ip_address' => $ipAddress, 'user_agent' => $userAgent, 'count' => 1]);
        }
    }

    public function getDailyVisitsToday()
    {
        // Mendapatkan tanggal hari ini
        $today = date('Y-m-d');

        // Query untuk mengambil jumlah pengunjung per hari ini
        $query = $this->select('SUM(count) as total_visits')
            ->where('tanggal', $today)
            ->groupBy('tanggal')
            ->get()
            ->getRowArray(); // Menggunakan getRowArray karena hanya satu baris yang diharapkan

        return $query['total_visits'] ?? 0; // Mengembalikan jumlah pengunjung atau 0 jika tidak ada data
    }


    public function getMonthlyVisits()
    {
        $currentYear = date('Y');

        // Query untuk mengambil jumlah pengunjung per bulan pada tahun yang sedang berlangsung
        $query = $this->db->query("
        SELECT MONTH(tanggal) AS bulan, SUM(count) AS total_visits
        FROM page_visits
        WHERE YEAR(tanggal) = $currentYear
        GROUP BY bulan
    ");

        return $query->getResultArray();
    }
}