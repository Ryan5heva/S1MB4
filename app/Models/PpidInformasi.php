<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpidInformasi extends Model
{
    use HasFactory;

    protected $table = 'ppid_informasi';

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisDokumen()
    {
        return $this->belongsTo(JenisDokumen::class, 'id_jenis_dokumen');
    }

    public function hasDokumen(): bool
    {
        return $this->jenis === 'dokumen' && ! empty($this->file);
    }
}