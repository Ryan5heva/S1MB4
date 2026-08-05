<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisDokumen extends Model
{
    use HasFactory;

    protected $table = 'jenis_dokumen';

    protected $fillable = [
        'jenis_dokumen',
        'status',
        'klasifikasi',
    ];

    protected function casts(): array
    {
        return [
            // Cast ke string agar perbandingan '0'/'1' tidak terkonversi ke boolean
            'status' => 'string',
        ];
    }

    // =========================================================
    //  Relasi
    // =========================================================

    /**
     * Semua informasi PPID yang terkait dengan jenis dokumen ini.
     */
    public function ppidInformasi()
    {
        return $this->hasMany(PpidInformasi::class, 'id_jenis_dokumen');
    }

    // =========================================================
    //  Helper Methods
    // =========================================================

    /**
     * Apakah jenis dokumen ini aktif (status = '1')?
     */
    public function isAktif(): bool
    {
        return $this->status === '1';
    }
}
