<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'aktivitas',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper biar gampang dipanggil dari controller mana aja.
     * Contoh pakai: ActivityLog::catat('Tambah Data', 'Menambahkan berita "Judul X"');
     */
    public static function catat(string $aktivitas, string $keterangan): void
    {
        static::create([
            'user_id'    => auth()->id(),
            'aktivitas'  => $aktivitas,
            'keterangan' => $keterangan,
        ]);
    }
}