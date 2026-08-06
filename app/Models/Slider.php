<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_tujuan',
        'gambar',
        'urutan',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relasi ke user yang mengunggah gambar ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: hanya gambar yang dicentang (status aktif), urut sesuai kolom 'urutan'.
     * Dipakai untuk menampilkan slider di halaman publik.
     */
    public function scopeTampil($query)
    {
        return $query->where('status', true)->orderBy('urutan')->orderBy('id');
    }
}