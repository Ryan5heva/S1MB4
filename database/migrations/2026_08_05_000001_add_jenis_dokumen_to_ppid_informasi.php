<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom id_jenis_dokumen dan tahun ke tabel ppid_informasi.
     *
     * id_jenis_dokumen — foreign key ke tabel jenis_dokumen (sudah ada di DB).
     *   Nullable agar data existing tidak rusak saat migrate.
     *   nullOnDelete: jika jenis_dokumen dihapus, kolom ini di-set null.
     *   Pakai integer biasa (bukan unsignedBigInteger) karena kolom `id`
     *   di tabel jenis_dokumen bertipe int(11), harus match persis.
     *
     * tahun — opsional; dibutuhkan untuk data SAKIP yang melebur ke sini,
     *   tapi tidak semua kategori PPID memerlukan tahun.
     *
     * Kolom 'kategori' lama TIDAK dihapus — dibiarkan sebagai cadangan
     * transisi data hingga mapping ke id_jenis_dokumen selesai diverifikasi.
     */
    public function up(): void
    {
        Schema::table('ppid_informasi', function (Blueprint $table) {
            // Referensi ke tabel jenis_dokumen (nullable untuk kompatibilitas data existing)
            $table->integer('id_jenis_dokumen')->nullable()->after('kategori');
            $table->foreign('id_jenis_dokumen')
                  ->references('id')
                  ->on('jenis_dokumen')
                  ->nullOnDelete();

            // Tahun dokumen (dipakai oleh kategori SAKIP dan sejenisnya)
            $table->unsignedSmallInteger('tahun')->nullable()->after('id_jenis_dokumen');
        });
    }

    /**
     * Batalkan migration — hapus kolom yang ditambahkan.
     */
    public function down(): void
    {
        Schema::table('ppid_informasi', function (Blueprint $table) {
            $table->dropForeign(['id_jenis_dokumen']);
            $table->dropColumn(['id_jenis_dokumen', 'tahun']);
        });
    }
};