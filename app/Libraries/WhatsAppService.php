<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;
use CodeIgniter\HTTP\RequestInterface;
use Config\Services;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;
    protected $adminNumber;

    public function __construct()
    {
        // Ambil pengaturan API URL dan API Key dari .env
        $this->apiUrl = getenv('WHATSAPP_API_URL');
        $this->apiKey = getenv('WHATSAPP_API_KEY');
        $this->adminNumber = getenv('WHATSAPP_ADMIN_NUMBER'); // Nomor admin dari file .env
    }

    public function sendMessageToAdmin($message)
    {
        // Menyiapkan data untuk request
        $data = [
            'target' => '6285765662373', // Nomor WhatsApp admin
            'message' => $message,
            'countryCode' => '62', // Kode negara Indonesia
        ];

        // Mengirim request POST ke API WhatsApp menggunakan cURL
        $client = Services::curlrequest();
        $response = $client->request('POST', $this->apiUrl, [
            'form_params' => $data,
            'headers' => [
                'Authorization' => $this->apiKey,
            ]
        ]);

        // Mengecek apakah request berhasil
        if ($response->getStatusCode() === 200) {
            return true;  // Pesan berhasil dikirim
        }

        return false; // Pesan gagal dikirim
    }
}
