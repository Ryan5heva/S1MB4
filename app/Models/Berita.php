<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'konten',
        'gambar',
        'user_id',
        'video_url',
    ];

    /**
     * Berita belongs to a User (author).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}