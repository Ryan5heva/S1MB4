<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Modifikasi tabel ppid_informasi untuk mendukung arsitektur baru:
 * - Nama Informasi bersifat TETAP (seeded) → is_fixed = true
 * - Admin hanya mengelola dokumen/link pada setiap baris
 * - Ketenagakerjaan adalah satu-satunya kategori yang boleh ditambah admin
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppid_informasi', function (Blueprint $table) {
            // Tandai apakah baris ini adalah data tetap (seeded) atau tambahan admin
            $table->boolean('is_fixed')->default(true)->after('urutan');

            // Urutan tampil kategori/section pada halaman index
            $table->unsignedTinyInteger('kategori_urutan')->default(0)->after('kategori');
        });

        // Ubah jenis menjadi nullable — baris seeded belum tentu memiliki dokumen
        DB::statement("ALTER TABLE ppid_informasi MODIFY jenis ENUM('dokumen','link') NULL");

        // Ubah user_id menjadi nullable — baris seeded tidak memiliki user
        DB::statement("ALTER TABLE ppid_informasi MODIFY user_id BIGINT UNSIGNED NULL");

        // Hapus foreign key constraint lama agar bisa re-add dengan nullable
        // (Laravel membuat FK bernama ppid_informasi_user_id_foreign)
        try {
            Schema::table('ppid_informasi', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Exception $e) {
            // FK mungkin tidak ada atau sudah dihapus, abaikan
        }

        // Re-add FK sebagai nullable
        Schema::table('ppid_informasi', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ppid_informasi', function (Blueprint $table) {
            $table->dropColumn(['is_fixed', 'kategori_urutan']);
            $table->dropForeign(['user_id']);
        });

        DB::statement("ALTER TABLE ppid_informasi MODIFY jenis ENUM('dokumen','link') NOT NULL");
        DB::statement("ALTER TABLE ppid_informasi MODIFY user_id BIGINT UNSIGNED NOT NULL");

        Schema::table('ppid_informasi', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
