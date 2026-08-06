<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'url_tujuan',
        'urutan',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relasi ke user yang menambahkan/mengubah slider ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: hanya slider yang aktif, urut sesuai kolom 'urutan'.
     * Dipakai untuk menampilkan banner di halaman publik.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true)->orderBy('urutan')->orderBy('id');
    }
}