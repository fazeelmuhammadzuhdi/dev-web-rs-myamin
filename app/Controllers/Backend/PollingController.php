<?php

namespace App\Controllers\Backend;

use App\Models\Polling;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;
use App\Models\PollVote;

class PollingController extends BaseController
{
    protected $polling;
    protected $pollvote;

    public function __construct()
    {
        $this->polling = new Polling();
        $this->pollvote = new PollVote();
        session();
    }

    public function index()
    {
        $data['title'] = 'Polling';

        return view('backend/polling/index', $data);
    }

    public function create()
    {
        return view('backend/polling/create');
    }

    public function vote()
    {
        $data['polling'] = $this->polling->where('status', 'PB')->findAll();
        return view('backend/polling/vote', $data);
    }

    public function voting()
    {
        $idPolling = $this->request->getPost('idpolling');
        $vote = $this->request->getPost('vote');

        $ipAddress = $this->request->getIPAddress();
        $sessionId = session_id();
        $cookieName = 'voted_' . $idPolling;

        // Periksa cookie
        if ($this->request->getCookie($cookieName)) {
            session()->setFlashdata('error', 'Anda Sudah Melakukan Voting Sebelumnya.');
            return redirect()->to('/');
        }

        if ($this->pollvote->hasVoted($idPolling, $ipAddress)) {
            session()->setFlashdata('error', 'Anda Sudah Melakukan Voting Sebelumnya.');
            return redirect()->to('/');
        }

        $poll = $this->polling->find($idPolling);

        switch ($vote) {
            case 'opa':
                $this->polling->update($idPolling, ['vopa' => $poll['vopa'] + 1]);
                break;
            case 'opb':
                $this->polling->update($idPolling, ['vopb' => $poll['vopb'] + 1]);
                break;
            case 'opc':
                $this->polling->update($idPolling, ['vopc' => $poll['vopc'] + 1]);
                break;
            case 'opd':
                $this->polling->update($idPolling, ['vopd' => $poll['vopd'] + 1]);
                break;
        }

        // Simpan voting ke tabel poll_votes
        $this->pollvote->saveVote($idPolling, $ipAddress, $sessionId);

        // Set cookie untuk menandai pengguna sudah voting
        $cookie = [
            'name'   => $cookieName,
            'value'  => '1',
            'expire' => time() + 30 * 24 * 60 * 60, // 1 bulan
        ];
        $this->response->setCookie($cookie);

        session()->setFlashdata('success', 'Terima kasih sudah berpartisipasi dalam polling ini');
        return redirect()->to('/');
    }


    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->polling->select('idpolling,pertanyaan,opa,opb,opc,opd,status');
            return DataTable::of($builder)

                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" pertanyaan="Hapus Data" onclick="hapus(\'' . $row->idpolling . '\',\'' . $row->pertanyaan . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" pertanyaan="Edit Data" onclick="edit(\'' . $row->idpolling . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $pertanyaan = $this->request->getVar('pertanyaan');
        $opa = $this->request->getVar('opa');
        $opb = $this->request->getVar('opb');
        $opc = $this->request->getVar('opc');
        $opd = $this->request->getVar('opd');
        $status = $this->request->getVar('status');

        $rules = $this->validate([

            'pertanyaan' => [
                'label' => 'Pertanyaan Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'opa' => [
                'label' => 'opa Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'opb' => [
                'label' => 'opb Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'opc' => [
                'label' => 'opc Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'opd' => [
                'label' => 'opd Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'status' => [
                'label' => 'status Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_pertanyaan' => $validation->getError('pertanyaan'),
                'error_opa' => $validation->getError('opa'),
                'error_opb' => $validation->getError('opb'),
                'error_opc' => $validation->getError('opc'),
                'error_opd' => $validation->getError('opd'),
                'error_status' => $validation->getError('status'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->polling->insert([
                'pertanyaan' => $pertanyaan,
                'status' => $status,
                'opa' => $opa,
                'opb' => $opb,
                'opc' => $opc,
                'opd' => $opd,
            ]);

            session()->setFlashdata('success', 'Data Polling Berhasil Di Tambahkan');
            return redirect()->to('/pollings');
        }
    }

    public function edit($id = null)
    {
        $data['polling'] = $this->polling->find($id);
        return view('backend/polling/edit', $data);
    }

    public function update()
    {

        $idPolling = $this->request->getVar('idpolling');
        $pertanyaan = $this->request->getVar('pertanyaan');
        $opa = $this->request->getVar('opa');
        $opb = $this->request->getVar('opb');
        $opc = $this->request->getVar('opc');
        $opd = $this->request->getVar('opd');
        $status = $this->request->getVar('status');


        $rules = $this->validate([

            'pertanyaan' => [
                'label' => 'Pertanyaan Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'opa' => [
                'label' => 'opa Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'opb' => [
                'label' => 'opb Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'opc' => [
                'label' => 'opc Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'opd' => [
                'label' => 'opd Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'status' => [
                'label' => 'status Polling',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_pertanyaan' => $validation->getError('pertanyaan'),
                'error_opa' => $validation->getError('opa'),
                'error_opb' => $validation->getError('opb'),
                'error_opc' => $validation->getError('opc'),
                'error_opd' => $validation->getError('opd'),
                'error_status' => $validation->getError('status'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $data = [
                'pertanyaan' => $pertanyaan,
                'status' => $status,
                'opa' => $opa,
                'opb' => $opb,
                'opc' => $opc,
                'opd' => $opd,
            ];

            $this->polling->update($idPolling, $data);

            session()->setFlashdata('success', 'Data Polling Berhasil Di Update');
            return redirect()->to('/pollings');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $keterangan = $this->polling->find($id);

            if ($keterangan) {
                $this->polling->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }

    
}
