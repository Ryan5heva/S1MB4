<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PpidInformasi extends Model
{
    use HasFactory;

    protected $table = 'ppid_informasi';

    /**
     * Urutan kanonik kategori untuk Informasi Setiap Saat.
     */
    public const KATEGORI_ORDER_SETIAP_SAAT = [
        'Informasi tentang profil dan gambaran umum Badan Publik',
        'Informasi tentang kegiatan dan kinerja Badan Publik',
        'Informasi tentang laporan keuangan Badan Publik',
    ];

    protected $fillable = [
        'jenis_menu',
        'id_jenis_dokumen',
        'nama_informasi',
        'deskripsi',
        'jenis',
        'file',
        'url',
        'status',
        'urutan',
        'tahun',
        'published_at',
        'is_fixed',
        'user_id',
    ];

    protected $casts = [
        'is_fixed'     => 'boolean',
        'published_at' => 'date',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisDokumen()
    {
        return $this->belongsTo(JenisDokumen::class, 'id_jenis_dokumen');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function hasDokumen(): bool
    {
        return $this->jenis === 'dokumen' && ! empty($this->file);
    }

    /**
     * Public URL to the stored file (null when no file).
     * Used by the public-facing JSON API.
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file ? Storage::disk('public')->url($this->file) : null;
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    /** Scope: semua item Informasi Berkala (dikelompokkan berdasarkan jenis_dokumen) */
    public function scopeBerkala($query)
    {
        return $query
            ->where('jenis_menu', 'berkala')
            ->where(function ($q) {
                // Izinkan id_jenis_dokumen = NULL (data seeded lama belum punya relasi ke jenis_dokumen)
                // Jika ada relasi, pastikan klasifikasinya mengandung kata 'berkala' (case-insensitive).
                // LIKE dipakai agar variasi seperti 'Berkala', 'Informasi Berkala', dsb. semua lolos.
                $q->whereNull('id_jenis_dokumen')
                  ->orWhereHas('jenisDokumen', fn ($jd) =>
                      $jd->whereRaw("LOWER(klasifikasi) LIKE '%berkala%'")
                  );
            });
    }

    /** Scope: semua item Informasi Serta Merta */
    public function scopeSertaMerta($query)
    {
        return $query->where('jenis_menu', 'serta_merta');
    }

    /** Scope: semua item Informasi Setiap Saat */
    public function scopeSetiapSaat($query)
    {
        return $query->where('jenis_menu', 'setiap_saat');
    }

    /** Scope: semua item Informasi Dikecualikan */
    public function scopeDikecualikan($query)
    {
        return $query->where('jenis_menu', 'dikecualikan');
    }

    /** Scope: semua item Laporan Akses Informasi */
    public function scopeLaporanAksesInformasi($query)
    {
        return $query->where('jenis_menu', 'laporan_akses_informasi');
    }

    /** Scope: hanya item berstatus publish */
    public function scopePublished($query)
    {
        return $query->where('status', 'publish');
    }
}