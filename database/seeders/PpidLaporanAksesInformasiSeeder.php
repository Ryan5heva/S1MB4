<?php

namespace Database\Seeders;

use App\Models\PpidInformasi;
use Illuminate\Database\Seeder;

/**
 * Seed 3 baris Laporan Akses Informasi yang bersifat TETAP (fixed).
 *
 * Admin TIDAK boleh menambah, menghapus, atau mengubah nama Perihal.
 * Admin hanya mengelola dokumen/link pada setiap Perihal.
 */
class PpidLaporanAksesInformasiSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Laporan Akses Informasi (LAI) Tahun 2025',
            'Laporan Akses Informasi (LAI) Tahun 2024',
            'Laporan Akses Informasi (LAI) Tahun 2023',
        ];

        foreach ($items as $urutan => $perihal) {
            PpidInformasi::updateOrCreate(
                [
                    'jenis_menu'     => 'laporan_akses_informasi',
                    'nama_informasi' => $perihal,
                    'is_fixed'       => true,
                ],
                [
                    'kategori'        => 'Laporan Akses Informasi',
                    'kategori_urutan' => 1,
                    'urutan'          => $urutan + 1,
                    'status'          => 'draft',
                    'jenis'           => null,
                    'file'            => null,
                    'url'             => null,
                    'user_id'         => null,
                ]
            );
        }

        $count = PpidInformasi::laporanAksesInformasi()->where('is_fixed', true)->count();
        $this->command->info("✅ Seeded {$count} fixed items Laporan Akses Informasi.");
    }
}
