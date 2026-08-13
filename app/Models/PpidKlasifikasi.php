<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model PpidKlasifikasi
 *
 * Merepresentasikan satu klasifikasi PPID yang ditampilkan
 * sebagai item dropdown "Klasifikasi Informasi" di navbar publik.
 *
 * Tabel ini bersifat konfigurasi — tidak mengubah struktur ppid_informasi.
 * Kolom jenis_menu_key adalah referensi ke nilai ENUM jenis_menu di ppid_informasi.
 */
class PpidKlasifikasi extends Model
{
    protected $table = 'ppid_klasifikasi';

    protected $fillable = [
        'nama_tampil',
        'slug_url',
        'jenis_menu_key',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif'  => 'boolean',
        'urutan' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    /**
     * Scope: hanya klasifikasi yang aktif (ditampilkan di navbar).
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // -------------------------------------------------------------------------
    // Helper
    // -------------------------------------------------------------------------

    /**
     * Konversi ke format yang digunakan navbar frontend.
     *
     * @return array{ label: string, href: string, urutan: int }
     */
    public function toNavItem(): array
    {
        return [
            'label'  => $this->nama_tampil,
            'href'   => $this->slug_url,
            'urutan' => $this->urutan,
        ];
    }
}
