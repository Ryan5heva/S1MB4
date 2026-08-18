<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom 'grup' ke tabel jenis_dokumen.
 *
 * Kolom ini digunakan untuk mengelompokkan jenis_dokumen ke dalam
 * <optgroup> pada dropdown kategori di halaman admin PPID.
 *
 * Nilai yang digunakan:
 *   - 'Informasi Berkala'
 *   - 'Informasi Serta Merta'
 *   - 'Informasi Setiap Saat'
 *   - 'Informasi Dikecualikan'
 *   - 'Laporan Akses Informasi'
 *   - 'Lainnya'
 *
 * Nullable agar tidak merusak data yang sudah ada sebelum seeder dijalankan.
 * Tidak mengubah kolom 'klasifikasi' yang sudah ada.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_dokumen', function (Blueprint $table) {
            $table->string('grup', 100)->nullable()->after('klasifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('jenis_dokumen', function (Blueprint $table) {
            $table->dropColumn('grup');
        });
    }
};
