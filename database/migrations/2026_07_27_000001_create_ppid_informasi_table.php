<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     *
     * Satu tabel untuk semua jenis informasi PPID:
     * - Informasi Berkala
     * - Informasi Serta Merta
     * - Informasi Setiap Saat
     * - Informasi Dikecualikan
     *
     * Dibedakan melalui kolom `jenis_menu` sehingga tidak perlu
     * membuat tabel baru untuk setiap kategori di masa mendatang.
     */
    public function up(): void
    {
        Schema::create('ppid_informasi', function (Blueprint $table) {
            $table->id();

            // Jenis menu PPID (untuk ekspansi masa depan)
            $table->enum('jenis_menu', [
                'berkala',
                'serta_merta',
                'setiap_saat',
                'dikecualikan',
            ])->default('berkala')->index();

            // Kelompok kategori, mis. "Profil Badan Publik"
            $table->string('kategori', 255);

            // Nama spesifik item informasi, mis. "Struktur Organisasi"
            $table->string('nama_informasi', 255);

            // Deskripsi tambahan (opsional)
            $table->text('deskripsi')->nullable();

            // Jenis konten: dokumen (file upload) atau link (URL eksternal)
            $table->enum('jenis', ['dokumen', 'link']);

            // Path file relatif dari storage/app/public (hanya jika jenis = dokumen)
            $table->string('file')->nullable();

            // URL (hanya jika jenis = link)
            $table->string('url', 2048)->nullable();

            // Status publikasi
            $table->enum('status', ['publish', 'draft'])->default('draft')->index();

            // Urutan tampil (ascending)
            $table->unsignedSmallInteger('urutan')->default(0);

            // Tanggal publish
            $table->timestamp('published_at')->nullable();

            // Relasi ke pengguna yang mencatat/mengubah data
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppid_informasi');
    }
};
