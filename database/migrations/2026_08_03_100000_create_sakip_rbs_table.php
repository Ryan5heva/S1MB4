<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel untuk dokumen SAKIP (Sistem Akuntabilitas Kinerja Instansi Pemerintah)
     * dan RB (Reformasi Birokrasi).
     *
     * Admin dapat menambah, mengubah, dan menghapus dokumen.
     * Dokumen dikelompokkan berdasarkan tahun.
     */
    public function up(): void
    {
        Schema::create('sakip_rbs', function (Blueprint $table) {
            $table->id();

            // Nama/judul dokumen, mis. "Perjanjian Kinerja"
            $table->string('jenis_dokumen', 255);

            // Kategori/klasifikasi, mis. "SAKIP" atau "Reformasi Birokrasi"
            $table->string('klasifikasi', 255)->nullable();

            // Tahun dokumen, mis. 2026
            $table->unsignedSmallInteger('tahun');

            // Path file relatif dari storage/app/public (nullable — bisa pakai link saja)
            $table->string('file')->nullable();

            // URL eksternal (nullable — bisa pakai file saja)
            $table->string('url', 2048)->nullable();

            // Status: '1' = aktif, '0' = nonaktif
            $table->enum('status', ['0', '1'])->default('1');

            // Pengguna terakhir yang mengubah (nullable agar tidak FK constraint saat delete user)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_rbs');
    }
};
