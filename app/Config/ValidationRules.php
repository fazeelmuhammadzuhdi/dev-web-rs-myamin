<?php

namespace App\Config;

/**
 * Centralized Validation Rules
 * 
 * This class contains common validation rules used across the application
 * to ensure consistency and maintainability.
 */
class ValidationRules
{
    /**
     * Common validation rules
     */
    public const REQUIRED = 'required';
    public const INTEGER = 'integer';
    public const EMAIL = 'valid_email';
    public const URL = 'valid_url';
    public const DATE = 'valid_date';
    public const UNIQUE = 'is_unique';
    public const MIN_LENGTH = 'min_length';
    public const MAX_LENGTH = 'max_length';
    public const IN_LIST = 'in_list';
    public const UPLOADED = 'uploaded';
    public const MAX_SIZE = 'max_size';
    public const MIME_IN = 'mime_in';

    /**
     * File upload rules
     */
    public const IMAGE_UPLOAD = 'uploaded[gambar]|max_size[gambar,1024]|mime_in[gambar,image/jpeg,image/png,image/jpg]';
    public const IMAGE_OPTIONAL = 'max_size[gambar,1024]|mime_in[gambar,image/jpeg,image/png,image/jpg]';

    /**
     * Status values
     */
    public const STATUS_VALUES = 'Y,N';
    public const NEWS_STATUS_VALUES = 'PB,DR';

    /**
     * Get news validation rules
     */
    public static function getNewsRules(bool $isCreate = true): array
    {
        $rules = [
            'judul' => [
                'label' => 'Judul Berita',
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Judul berita harus diisi',
                    'min_length' => 'Judul berita minimal 3 karakter',
                    'max_length' => 'Judul berita maksimal 255 karakter'
                ]
            ],
            'tanggal' => [
                'label' => 'Tanggal Berita',
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'konten' => [
                'label' => 'Konten Berita',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Konten berita harus diisi',
                    'min_length' => 'Konten berita minimal 10 karakter'
                ]
            ],
            'kategori_id' => [
                'label' => 'Kategori Berita',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Kategori harus dipilih',
                    'integer' => 'Kategori ID harus berupa angka'
                ]
            ],
            'status' => [
                'label' => 'Status Berita',
                'rules' => 'required|in_list[' . self::NEWS_STATUS_VALUES . ']',
                'errors' => [
                    'required' => 'Status harus dipilih',
                    'in_list' => 'Status harus Publish atau Draft'
                ]
            ]
        ];

        if ($isCreate) {
            $rules['gambar'] = [
                'label' => 'Gambar Berita',
                'rules' => self::IMAGE_UPLOAD,
                'errors' => [
                    'uploaded' => 'Gambar berita harus diisi',
                    'max_size' => 'Ukuran gambar maksimum 1MB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        } else {
            $rules['gambar'] = [
                'label' => 'Gambar Berita',
                'rules' => self::IMAGE_OPTIONAL,
                'errors' => [
                    'max_size' => 'Ukuran gambar maksimum 1MB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        }

        return $rules;
    }

    /**
     * Get doctor validation rules
     */
    public static function getDoctorRules(bool $isCreate = true): array
    {
        $rules = [
            'nip' => [
                'label' => 'NIP Dokter',
                'rules' => 'required|min_length[3]|max_length[20]',
                'errors' => [
                    'required' => 'NIP dokter harus diisi',
                    'min_length' => 'NIP minimal 3 karakter',
                    'max_length' => 'NIP maksimal 20 karakter'
                ]
            ],
            'nama' => [
                'label' => 'Nama Dokter',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama dokter harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'spesialis_id' => [
                'label' => 'Spesialis',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Spesialis harus dipilih',
                    'integer' => 'Spesialis ID harus berupa angka'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ],
            'status' => [
                'label' => 'Status',
                'rules' => 'required|in_list[' . self::STATUS_VALUES . ']',
                'errors' => [
                    'required' => 'Status harus dipilih',
                    'in_list' => 'Status harus Aktif atau Tidak Aktif'
                ]
            ]
        ];

        if ($isCreate) {
            $rules['gambar'] = [
                'label' => 'Gambar Dokter',
                'rules' => self::IMAGE_UPLOAD,
                'errors' => [
                    'uploaded' => 'Gambar dokter harus diisi',
                    'max_size' => 'Ukuran gambar maksimum 1MB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        } else {
            $rules['gambar'] = [
                'label' => 'Gambar Dokter',
                'rules' => self::IMAGE_OPTIONAL,
                'errors' => [
                    'max_size' => 'Ukuran gambar maksimum 1MB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        }

        return $rules;
    }

    /**
     * Get user validation rules
     */
    public static function getUserRules(): array
    {
        return [
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[3]|max_length[50]|is_unique[user.username]',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'min_length' => 'Username minimal 3 karakter',
                    'max_length' => 'Username maksimal 50 karakter',
                    'is_unique' => 'Username sudah digunakan'
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[user.email]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah digunakan'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ]
        ];
    }

    /**
     * Get category validation rules
     */
    public static function getCategoryRules(): array
    {
        return [
            'title' => [
                'label' => 'Nama Kategori',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama kategori harus diisi',
                    'min_length' => 'Nama kategori minimal 3 karakter',
                    'max_length' => 'Nama kategori maksimal 100 karakter'
                ]
            ],
            'status' => [
                'label' => 'Status',
                'rules' => 'required|in_list[' . self::STATUS_VALUES . ']',
                'errors' => [
                    'required' => 'Status harus dipilih',
                    'in_list' => 'Status harus Aktif atau Tidak Aktif'
                ]
            ]
        ];
    }

    /**
     * Get common error messages
     */
    public static function getCommonErrors(): array
    {
        return [
            'required' => '{field} tidak boleh kosong',
            'integer' => '{field} harus berupa angka',
            'valid_email' => 'Format email tidak valid',
            'valid_date' => 'Format tanggal tidak valid',
            'is_unique' => '{field} sudah digunakan',
            'min_length' => '{field} minimal {param} karakter',
            'max_length' => '{field} maksimal {param} karakter',
            'in_list' => '{field} tidak valid',
            'uploaded' => '{field} harus diisi',
            'max_size' => 'Ukuran {field} maksimum {param}KB',
            'mime_in' => 'Format {field} tidak diizinkan'
        ];
    }
}