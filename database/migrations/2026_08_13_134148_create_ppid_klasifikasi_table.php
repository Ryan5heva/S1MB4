<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel konfigurasi klasifikasi PPID untuk navbar publik.
 *
 * Tabel ini TIDAK mengubah struktur ppid_informasi — kolom jenis_menu
 * tetap ENUM. Tabel ini hanya mengontrol:
 *   - Nama tampil di navbar (label)
 *   - Slug URL tujuan (href di React)
 *   - Urutan tampil di dropdown
 *   - Aktif/nonaktif (tampil/tidak di navbar)
 *
 * Kolom jenis_menu_key menghubungkan baris ini ke nilai ENUM
 * pada tabel ppid_informasi (untuk referensi, tidak digunakan sebagai FK).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ppid_klasifikasi', function (Blueprint $table) {
            $table->id();

            // Label yang ditampilkan di navbar, mis. "Informasi Berkala"
            $table->string('nama_tampil', 150);

            // Slug URL tujuan di frontend, mis. "/ppid/berkala"
            $table->string('slug_url', 255);

            // Nilai jenis_menu di tabel ppid_informasi (referensi, bukan FK)
            // mis. "berkala", "serta_merta", "setiap_saat", dll.
            $table->string('jenis_menu_key', 50)->unique();

            // Urutan tampil di dropdown (ascending)
            $table->unsignedTinyInteger('urutan')->default(0)->index();

            // Apakah klasifikasi ini ditampilkan di navbar publik
            $table->boolean('aktif')->default(true)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppid_klasifikasi');
    }
};
