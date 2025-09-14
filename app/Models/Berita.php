<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Berita Model
 * 
 * Handles news articles with proper validation and business logic
 */
class Berita extends Model
{
    // Constants for better maintainability
    public const STATUS_PUBLISHED = 'PB';
    public const STATUS_DRAFT = 'DR';
    public const STATUS_ACTIVE = 'Y';
    
    protected $table            = 'berita';
    protected $primaryKey       = 'idberita';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'judul',
        'user_id',
        'tanggal',
        'konten',
        'kategori_id',
        'status',
        'gambar',
        'thumbnail',
        'vimg',
        'viewberita',
        'slug',
        'created_at',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'judul'         => 'required|min_length[3]|max_length[255]',
        'user_id'       => 'required|integer',
        'tanggal'       => 'required|valid_date',
        'konten'        => 'required|min_length[10]',
        'kategori_id'   => 'required|integer',
        'status'        => 'required|in_list[PB,DR]',
        'slug'          => 'required|is_unique[berita.slug,idberita,{idberita}]'
    ];
    
    protected $validationMessages   = [
        'judul' => [
            'required' => 'Judul berita harus diisi',
            'min_length' => 'Judul berita minimal 3 karakter',
            'max_length' => 'Judul berita maksimal 255 karakter'
        ],
        'user_id' => [
            'required' => 'User ID harus diisi',
            'integer' => 'User ID harus berupa angka'
        ],
        'tanggal' => [
            'required' => 'Tanggal harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'konten' => [
            'required' => 'Konten berita harus diisi',
            'min_length' => 'Konten berita minimal 10 karakter'
        ],
        'kategori_id' => [
            'required' => 'Kategori harus dipilih',
            'integer' => 'Kategori ID harus berupa angka'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status harus Publish atau Draft'
        ],
        'slug' => [
            'required' => 'Slug harus diisi',
            'is_unique' => 'Slug sudah digunakan'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get news with category and user information for admin
     */
    public function getBerita()
    {
        return $this->db->table('berita')
            ->join('kategori', 'berita.kategori_id = kategori.idkategori')
            ->join('user', 'berita.user_id = user.iduser')
            ->select('berita.idberita, berita.judul, kategori.title, berita.tanggal, berita.status, berita.gambar, user.nama')
            ->orderBy('berita.tanggal', 'desc')
            ->orderBy('berita.idberita', 'desc');
    }

    /**
     * Increment view count for a news article
     */
    public function incrementViewCount(int $id): bool
    {
        $currentViews = $this->select('viewberita')->find($id)['viewberita'] ?? 0;
        
        return $this->update($id, [
            'viewberita' => $currentViews + 1
        ]);
    }

    /**
     * Get news count by category
     */
    public function countBeritaByKategori(): array
    {
        return $this->db->table('berita')
            ->select('kategori.title, kategori.idkategori, COUNT(berita.kategori_id) as total')
            ->join('kategori', 'berita.kategori_id = kategori.idkategori')
            ->where('kategori.status', self::STATUS_ACTIVE)
            ->groupBy('berita.kategori_id')
            ->get()
            ->getResultArray();
    }

    /**
     * Get published news for frontend
     */
    public function getPublishedNews(int $limit = null): array
    {
        $query = $this->where('status', self::STATUS_PUBLISHED)
            ->orderBy('tanggal', 'desc')
            ->orderBy('idberita', 'desc');
            
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->findAll();
    }

    /**
     * Get news by category
     */
    public function getNewsByCategory(int $kategoriId, int $limit = null): array
    {
        $query = $this->where('kategori_id', $kategoriId)
            ->where('status', self::STATUS_PUBLISHED)
            ->orderBy('tanggal', 'desc');
            
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->findAll();
    }

    /**
     * Get most viewed news
     */
    public function getMostViewedNews(int $limit = 8): array
    {
        return $this->where('status', self::STATUS_PUBLISHED)
            ->orderBy('viewberita', 'desc')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Search news by title
     */
    public function searchNews(string $keyword, int $limit = null): array
    {
        $query = $this->like('judul', $keyword)
            ->where('status', self::STATUS_PUBLISHED)
            ->orderBy('tanggal', 'desc');
            
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->findAll();
    }

    // public function getGrafikBerita()
    // {
    //     $currentYear = date('Y');

    //     // Query untuk mengambil jumlah postingan berita per bulan pada tahun yang sedang berlangsung
    //     $query = $this->db->query(
    //         "
    //          SELECT MONTH(berita.tanggal) AS bulan,
    //         kategori.title AS kategori,
    //         COUNT(*) AS jumlah
    //     FROM
    //         berita
    //     JOIN
    //         kategori ON berita.kategori_id = kategori.idkategori
    //     WHERE
    //         YEAR(berita.tanggal) = $currentYear
    //     GROUP BY
    //         bulan, kategori;
    //     "
    //     );

    //     return $query->getResultArray();
    // }

    /**
     * Get news statistics for charts
     */
    public function getGrafikBerita(): array
    {
        $currentYear = date('Y');

        $query = $this->db->query(
            "SELECT
                MONTH(berita.tanggal) AS bulan,
                kategori.title AS kategori,
                COUNT(*) AS jumlah
            FROM berita
            JOIN kategori ON berita.kategori_id = kategori.idkategori
            WHERE YEAR(berita.tanggal) = ?
            GROUP BY MONTH(berita.tanggal), kategori.title
            ORDER BY bulan ASC",
            [$currentYear]
        );

        return $query->getResultArray();
    }

    /**
     * Get news by slug
     */
    public function getNewsBySlug(string $slug): ?array
    {
        return $this->where('slug', $slug)->first();
    }

    /**
     * Get related news (same category, excluding current news)
     */
    public function getRelatedNews(int $kategoriId, string $currentSlug, int $limit = 4): array
    {
        return $this->where('kategori_id', $kategoriId)
            ->where('slug !=', $currentSlug)
            ->where('status', self::STATUS_PUBLISHED)
            ->orderBy('tanggal', 'desc')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get news statistics
     */
    public function getNewsStats(): array
    {
        return [
            'total' => $this->countAllResults(),
            'published' => $this->where('status', self::STATUS_PUBLISHED)->countAllResults(),
            'draft' => $this->where('status', self::STATUS_DRAFT)->countAllResults(),
            'this_month' => $this->where('MONTH(tanggal)', date('m'))
                ->where('YEAR(tanggal)', date('Y'))
                ->countAllResults()
        ];
    }
}
