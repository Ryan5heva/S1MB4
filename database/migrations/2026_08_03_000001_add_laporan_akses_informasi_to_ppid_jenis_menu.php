<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Tambah nilai 'laporan_akses_informasi' ke ENUM jenis_menu
 * pada tabel ppid_informasi agar modul Laporan Akses Informasi
 * dapat menggunakan tabel yang sama.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE ppid_informasi
            MODIFY COLUMN jenis_menu
            ENUM('berkala','serta_merta','setiap_saat','dikecualikan','laporan_akses_informasi')
            NOT NULL DEFAULT 'berkala'
        ");
    }

    public function down(): void
    {
        // Hapus semua baris laporan_akses_informasi sebelum rollback ENUM
        DB::table('ppid_informasi')->where('jenis_menu', 'laporan_akses_informasi')->delete();

        DB::statement("
            ALTER TABLE ppid_informasi
            MODIFY COLUMN jenis_menu
            ENUM('berkala','serta_merta','setiap_saat','dikecualikan')
            NOT NULL DEFAULT 'berkala'
        ");
    }
};
