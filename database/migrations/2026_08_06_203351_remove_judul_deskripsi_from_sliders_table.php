<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus kolom judul dan deskripsi dari tabel sliders.
     * Kedua kolom ini tidak lagi digunakan — frontend hanya menampilkan gambar.
     */
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['judul', 'deskripsi']);
        });
    }

    /**
     * Kembalikan kolom jika migration di-rollback.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('judul')->after('id');
            $table->text('deskripsi')->nullable()->after('judul');
        });
    }
};
