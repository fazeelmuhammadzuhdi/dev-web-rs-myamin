<?php

namespace App\Models;

use CodeIgniter\Model;

class Polling extends Model
{
    protected $table            = 'polling';
    protected $primaryKey       = 'idpolling';

    protected $allowedFields    = [
        'idpolling',
        'pertanyaan',
        'opa',
        'opb',
        'opc',
        'opd',
        'status',
        'vopa',
        'vopb',
        'vopc',
        'vopd',
    ];

    public function getPollingData()
    {
        return $this->where('status', 'PB')->findAll();
    }

    public function getFormattedPollingData()
    {
        $pollData = $this->getPollingData();

        $polling = [];
        if (count($pollData) > 0) {
            $poll = $pollData[0];
            $totalVotes = $poll['vopa'] + $poll['vopb'] + $poll['vopc'] + $poll['vopd'];
            $polling = [
                'pertanyaan' => $poll['pertanyaan'],
                'options' => [
                    'opa' => [
                        'label' => $poll['opa'],
                        'label' => $poll['opa'],
                        'votes' => $poll['vopa'],
                        'percentage' => $totalVotes > 0 ? ($poll['vopa'] / $totalVotes) * 100 : 0,
                    ],
                    'opb' => [
                        'label' => $poll['opb'],
                        'votes' => $poll['vopb'],
                        'percentage' => $totalVotes > 0 ? ($poll['vopb'] / $totalVotes) * 100 : 0,
                    ],
                    'opc' => [
                        'label' => $poll['opc'],
                        'votes' => $poll['vopc'],
                        'percentage' => $totalVotes > 0 ? ($poll['vopc'] / $totalVotes) * 100 : 0,
                    ],
                    'opd' => [
                        'label' => $poll['opd'],
                        'votes' => $poll['vopd'],
                        'percentage' => $totalVotes > 0 ? ($poll['vopd'] / $totalVotes) * 100 : 0,
                    ],
                ],
            ];
        }

        return $polling;
    }
}
