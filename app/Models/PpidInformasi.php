<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PpidInformasi extends Model
{
    use HasFactory;

    protected $table = 'ppid_informasi';

    protected $fillable = [
        'jenis_menu',
        'kategori',
        'kategori_urutan',
        'nama_informasi',
        'deskripsi',
        'jenis',
        'file',
        'url',
        'status',
        'urutan',
        'is_fixed',
        'published_at',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'published_at'    => 'datetime',
            'urutan'          => 'integer',
            'kategori_urutan' => 'integer',
            'is_fixed'        => 'boolean',
        ];
    }

    // =========================================================
    //  Konstanta Referensi
    // =========================================================

    /**
     * Label untuk setiap jenis menu PPID.
     */
    const JENIS_MENU = [
        'berkala'      => 'Informasi Berkala',
        'serta_merta'  => 'Informasi Serta Merta',
        'setiap_saat'  => 'Informasi Setiap Saat',
        'dikecualikan' => 'Informasi Dikecualikan',
    ];

    /**
     * Urutan kanonical seksi Informasi Berkala.
     * Digunakan untuk memastikan seksi selalu tampil dalam urutan yang benar.
     */
    const KATEGORI_ORDER_BERKALA = [
        'Profil Badan Publik',
        'Program dan/atau Kegiatan',
        'Kinerja Badan Publik',
        'Laporan Keuangan',
        'Laporan Akses Informasi Publik',
        'Prosedur Permohonan Informasi Publik',
        'Tata Cara Pengaduan Penyalahgunaan Wewenang atau Pelanggaran Badan Publik',
        'Pengadaan Barang dan Jasa Pemerintah',
        'Ketenagakerjaan',
        'Prosedur Peringatan Dini dan Prosedur Evakuasi Keadaan Darurat',
    ];

    /**
     * Urutan kanonical seksi Informasi Setiap Saat.
     * Digunakan untuk memastikan 3 tabel selalu tampil dalam urutan yang benar.
     */
    const KATEGORI_ORDER_SETIAP_SAAT = [
        'Daftar Informasi Publik (DIP)',
        'Laporan Survei Kepuasan Masyarakat (SKM)',
        'Standar Pelayanan',
    ];

    // =========================================================
    //  Relasi
    // =========================================================

    /**
     * Pengguna yang terakhir mengubah baris ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =========================================================
    //  Query Scopes
    // =========================================================

    public function scopeBerkala(Builder $query): Builder
    {
        return $query->where('jenis_menu', 'berkala');
    }

    public function scopeSertaMerta(Builder $query): Builder
    {
        return $query->where('jenis_menu', 'serta_merta');
    }

    public function scopeSetiapSaat(Builder $query): Builder
    {
        return $query->where('jenis_menu', 'setiap_saat');
    }

    public function scopeDikecualikan(Builder $query): Builder
    {
        return $query->where('jenis_menu', 'dikecualikan');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'publish');
    }

    // =========================================================
    //  Helper Methods / Accessors
    // =========================================================

    /**
     * Label jenis menu yang ramah untuk ditampilkan.
     */
    public function getJenisMenuLabelAttribute(): string
    {
        return self::JENIS_MENU[$this->jenis_menu] ?? $this->jenis_menu;
    }

    /**
     * Apakah berkas file berupa PDF?
     */
    public function isPdf(): bool
    {
        return $this->jenis === 'dokumen'
            && $this->file
            && strtolower(pathinfo($this->file, PATHINFO_EXTENSION)) === 'pdf';
    }

    /**
     * Nama file asli (basename) tanpa path lengkap.
     */
    public function getFileNameAttribute(): ?string
    {
        return $this->file ? basename($this->file) : null;
    }

    /**
     * Apakah baris ini sudah memiliki dokumen atau link?
     */
    public function hasDokumen(): bool
    {
        return $this->jenis !== null
            && ($this->file !== null || $this->url !== null);
    }
}
