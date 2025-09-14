<?php

namespace App\Models;

use CodeIgniter\Model;



class PollVote extends Model
{
    protected $table = 'poll_votes';
    protected $allowedFields = ['idpolling', 'ip_address', 'voted_at', 'session_id'];

    // public function hasVoted($idpolling, $ipAddress, $sessionId)
    // {
    //     return $this->where('idpolling', $idpolling)
    //         ->where('ip_address', $ipAddress)
    //         ->where('session_id', $sessionId)
    //         ->countAllResults() > 0;
    // }

    // public function saveVote($idpolling, $ipAddress, $sessionId)
    // {
    //     $data = [
    //         'idpolling' => $idpolling,
    //         'ip_address' => $ipAddress,
    //         'session_id' => $sessionId,
    //         'voted_at' => date('Y-m-d H:i:s')
    //     ];
    //     $this->insert($data);
    // }


    public function hasVoted($idpolling, $ipAddress)
    {
        $currentMonth = date('Y-m');

        return $this->where('idpolling', $idpolling)
            ->where('ip_address', $ipAddress)
            ->where('DATE_FORMAT(voted_at, "%Y-%m")', $currentMonth)
            ->countAllResults() > 0;
    }

    public function saveVote($idpolling, $ipAddress)
    {
        if ($this->hasVoted($idpolling, $ipAddress)) {
            return false;
        }

        $data = [
            'idpolling' => $idpolling,
            'ip_address' => $ipAddress,
            'voted_at' => date('Y-m-d H:i:s')
        ];
        $this->insert($data);

        return true;
    }

    public function getMonthlyVoting()
    {
        $currentYear = date('Y');

        // Query untuk mengambil jumlah pengunjung per bulan pada tahun yang sedang berlangsung
        $query = $this->db->query("
        SELECT MONTH(voted_at) AS bulan, COUNT(*) AS total_votes
        FROM poll_votes
        WHERE YEAR(voted_at) = $currentYear
        GROUP BY bulan
    ");


        return $query->getResultArray();
    }
}
