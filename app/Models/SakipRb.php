<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SakipRb extends Model
{
    use HasFactory;

    protected $table = 'sakip_rbs';

    protected $fillable = [
        'jenis_dokumen',
        'klasifikasi',
        'tahun',
        'file',
        'url',
        'status',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            // Cast ke string agar perbandingan '0'/'1' tidak terkonversi ke boolean
            'status' => 'string',
            'tahun'  => 'integer',
        ];
    }

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
    //  Helper Methods / Accessors
    // =========================================================

    /**
     * Apakah baris ini sudah memiliki dokumen (file) atau tautan (url)?
     */
    public function hasDokumen(): bool
    {
        return $this->file !== null || $this->url !== null;
    }

    /**
     * Nama file asli (basename) tanpa path lengkap.
     */
    public function getFileNameAttribute(): ?string
    {
        return $this->file ? basename($this->file) : null;
    }

    /**
     * Daftar tahun unik yang tersedia di tabel, diurutkan descending.
     * Jika tabel kosong, kembalikan array berisi tahun sekarang.
     *
     * @return array<int>
     */
    public static function tahunTersedia(): array
    {
        $tahun = static::orderByDesc('tahun')
            ->distinct()
            ->pluck('tahun')
            ->map(fn($t) => (int) $t)
            ->toArray();

        return $tahun ?: [now()->year];
    }
}
