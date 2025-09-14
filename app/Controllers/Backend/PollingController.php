<?php

namespace App\Controllers\Backend;

use App\Models\Polling;
use App\Models\PollVote;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * PollingController handles polling management functionality
 * 
 * This controller manages polling creation, editing, deletion, voting system,
 * and vote tracking with IP address and cookie-based duplicate prevention.
 */
class PollingController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_PUBLISHED = 'PB';
    private const STATUS_DRAFT = 'DR';
    private const COOKIE_EXPIRE_TIME = 30 * 24 * 60 * 60; // 30 days
    
    // Model instances
    private Polling $pollingModel;
    private PollVote $pollVoteModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->pollingModel = new Polling();
        $this->pollVoteModel = new PollVote();
        session();
    }

    /**
     * Display polling index page
     */
    public function index()
    {
        $data = ['title' => 'Polling'];
        return view('backend/polling/index', $data);
    }

    /**
     * Display polling creation form
     */
    public function create()
    {
        return view('backend/polling/create');
    }

    /**
     * Display voting page
     */
    public function vote()
    {
        $data = [
            'polling' => $this->pollingModel->where('status', self::STATUS_PUBLISHED)->findAll()
        ];
        return view('backend/polling/vote', $data);
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->pollingModel->select('idpolling,pertanyaan,opa,opb,opc,opd,status');
        
        return DataTable::of($builder)
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idpolling, $row->pertanyaan);
            }, 'last')
            ->toJson();
    }

    /**
     * Format action buttons
     */
    private function formatActionButtons(int $id, string $pertanyaan): string
    {
        return '<div class="d-flex" role="group">
            <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $id . '\',\'' . esc($pertanyaan) . '\')">
                <i class="feather icon-trash-2"></i>
            </button>
            <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $id . '\')">
                <i class="feather icon-edit"></i>
            </button>
        </div>';
    }

    /**
     * Get polling validation rules
     */
    private function getPollingValidationRules(): array
    {
        return [
            'pertanyaan' => [
                'label' => 'Pertanyaan Polling',
                'rules' => 'required|min_length[10]|max_length[255]',
                'errors' => [
                    'required' => 'Pertanyaan polling harus diisi',
                    'min_length' => 'Pertanyaan minimal 10 karakter',
                    'max_length' => 'Pertanyaan maksimal 255 karakter'
                ]
            ],
            'opa' => [
                'label' => 'Opsi A',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Opsi A harus diisi',
                    'min_length' => 'Opsi A minimal 3 karakter',
                    'max_length' => 'Opsi A maksimal 100 karakter'
                ]
            ],
            'opb' => [
                'label' => 'Opsi B',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Opsi B harus diisi',
                    'min_length' => 'Opsi B minimal 3 karakter',
                    'max_length' => 'Opsi B maksimal 100 karakter'
                ]
            ],
            'opc' => [
                'label' => 'Opsi C',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Opsi C harus diisi',
                    'min_length' => 'Opsi C minimal 3 karakter',
                    'max_length' => 'Opsi C maksimal 100 karakter'
                ]
            ],
            'opd' => [
                'label' => 'Opsi D',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Opsi D harus diisi',
                    'min_length' => 'Opsi D minimal 3 karakter',
                    'max_length' => 'Opsi D maksimal 100 karakter'
                ]
            ],
            'status' => [
                'label' => 'Status Polling',
                'rules' => 'required|in_list[PB,DR]',
                'errors' => [
                    'required' => 'Status polling harus diisi',
                    'in_list' => 'Status harus Publish atau Draft'
                ]
            ]
        ];
    }

    /**
     * Save new polling
     */
    public function save()
    {
        $data = $this->getFormData(['pertanyaan', 'opa', 'opb', 'opc', 'opd', 'status']);

        $rules = $this->getPollingValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['pertanyaan', 'opa', 'opb', 'opc', 'opd', 'status']);
        }

        $this->pollingModel->insert([
            'pertanyaan' => $data['pertanyaan'],
            'status' => $data['status'],
            'opa' => $data['opa'],
            'opb' => $data['opb'],
            'opc' => $data['opc'],
            'opd' => $data['opd'],
        ]);

        return $this->setSuccessMessage('Data Polling Berhasil Ditambahkan', '/pollings');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['polling' => $this->pollingModel->find($id)];
        return view('backend/polling/edit', $data);
    }

    /**
     * Update existing polling
     */
    public function update()
    {
        $data = $this->getFormData(['idpolling', 'pertanyaan', 'opa', 'opb', 'opc', 'opd', 'status']);
        $idPolling = $data['idpolling'];

        $rules = $this->getPollingValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['pertanyaan', 'opa', 'opb', 'opc', 'opd', 'status']);
        }

        $updateData = [
            'pertanyaan' => $data['pertanyaan'],
            'status' => $data['status'],
            'opa' => $data['opa'],
            'opb' => $data['opb'],
            'opc' => $data['opc'],
            'opd' => $data['opd'],
        ];

        $this->pollingModel->update($idPolling, $updateData);

        return $this->setSuccessMessage('Data Polling Berhasil Di Update', '/pollings');
    }

    /**
     * Delete polling
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $polling = $this->pollingModel->find($id);

        if (!$polling) {
            return $this->jsonError('Data polling tidak ditemukan', 404);
        }

        $this->pollingModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }

    /**
     * Process voting
     */
    public function voting()
    {
        $idPolling = $this->request->getPost('idpolling');
        $vote = $this->request->getPost('vote');

        if (!$idPolling || !$vote) {
            session()->setFlashdata('error', 'Data voting tidak valid.');
            return redirect()->to('/');
        }

        $ipAddress = $this->request->getIPAddress();
        $sessionId = session_id();
        $cookieName = 'voted_' . $idPolling;

        // Check cookie
        if ($this->request->getCookie($cookieName)) {
            session()->setFlashdata('error', 'Anda Sudah Melakukan Voting Sebelumnya.');
            return redirect()->to('/');
        }

        // Check if already voted
        if ($this->pollVoteModel->hasVoted($idPolling, $ipAddress)) {
            session()->setFlashdata('error', 'Anda Sudah Melakukan Voting Sebelumnya.');
            return redirect()->to('/');
        }

        $poll = $this->pollingModel->find($idPolling);
        if (!$poll) {
            session()->setFlashdata('error', 'Polling tidak ditemukan.');
            return redirect()->to('/');
        }

        // Update vote count
        $this->updateVoteCount($idPolling, $vote, $poll);

        // Save vote record
        $this->pollVoteModel->saveVote($idPolling, $ipAddress, $sessionId);

        // Set cookie to prevent duplicate voting
        $this->setVotingCookie($cookieName);

        session()->setFlashdata('success', 'Terima kasih sudah berpartisipasi dalam polling ini');
        return redirect()->to('/');
    }

    /**
     * Update vote count based on selected option
     */
    private function updateVoteCount(int $idPolling, string $vote, array $poll): void
    {
        $updateData = [];
        
        switch ($vote) {
            case 'opa':
                $updateData['vopa'] = $poll['vopa'] + 1;
                break;
            case 'opb':
                $updateData['vopb'] = $poll['vopb'] + 1;
                break;
            case 'opc':
                $updateData['vopc'] = $poll['vopc'] + 1;
                break;
            case 'opd':
                $updateData['vopd'] = $poll['vopd'] + 1;
                break;
            default:
                throw new \InvalidArgumentException('Invalid vote option');
        }

        $this->pollingModel->update($idPolling, $updateData);
    }

    /**
     * Set voting cookie
     */
    private function setVotingCookie(string $cookieName): void
    {
        $cookie = [
            'name'   => $cookieName,
            'value'  => '1',
            'expire' => time() + self::COOKIE_EXPIRE_TIME,
        ];
        $this->response->setCookie($cookie);
    }
}