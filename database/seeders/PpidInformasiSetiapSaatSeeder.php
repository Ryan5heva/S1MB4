<?php

namespace Database\Seeders;

use App\Models\PpidInformasi;
use Illuminate\Database\Seeder;

/**
 * Seed baris Informasi Setiap Saat yang bersifat TETAP (fixed).
 *
 * Terdiri dari 3 kategori:
 * 1. Daftar Informasi Publik (DIP)
 * 2. Laporan Survei Kepuasan Masyarakat (SKM)
 * 3. Standar Pelayanan
 *
 * Admin TIDAK boleh menambah, menghapus, atau mengubah daftar Perihal.
 * Admin hanya mengelola dokumen/link pada setiap Perihal.
 */
class PpidInformasiSetiapSaatSeeder extends Seeder
{
    public function run(): void
    {
        // ── Kategori 1: Daftar Informasi Publik (DIP) ──
        $dipItems = [
            'Daftar Informasi Publik (DIP) Tahun 2024',
            'Daftar Informasi Publik (DIP) Tahun 2025',
            'Daftar Informasi Publik (DIP) Tahun 2026',
        ];

        foreach ($dipItems as $urutan => $perihal) {
            PpidInformasi::updateOrCreate(
                [
                    'jenis_menu'     => 'setiap_saat',
                    'nama_informasi' => $perihal,
                    'is_fixed'       => true,
                ],
                [
                    'kategori'        => 'Daftar Informasi Publik (DIP)',
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

        // ── Kategori 2: Laporan Survei Kepuasan Masyarakat (SKM) ──
        $skmItems = [
            'Laporan Survei Kepuasan Masyarakat (SKM) Tahun 2023',
            'Laporan Survei Kepuasan Masyarakat (SKM) Tahun 2024',
            'Laporan Survei Kepuasan Masyarakat (SKM) Tahun 2025',
        ];

        foreach ($skmItems as $urutan => $perihal) {
            PpidInformasi::updateOrCreate(
                [
                    'jenis_menu'     => 'setiap_saat',
                    'nama_informasi' => $perihal,
                    'is_fixed'       => true,
                ],
                [
                    'kategori'        => 'Laporan Survei Kepuasan Masyarakat (SKM)',
                    'kategori_urutan' => 2,
                    'urutan'          => $urutan + 1,
                    'status'          => 'draft',
                    'jenis'           => null,
                    'file'            => null,
                    'url'             => null,
                    'user_id'         => null,
                ]
            );
        }

        // ── Kategori 3: Standar Pelayanan ──
        $spItems = [
            'Standar Pelayanan Bakorwil I Madiun',
        ];

        foreach ($spItems as $urutan => $perihal) {
            PpidInformasi::updateOrCreate(
                [
                    'jenis_menu'     => 'setiap_saat',
                    'nama_informasi' => $perihal,
                    'is_fixed'       => true,
                ],
                [
                    'kategori'        => 'Standar Pelayanan',
                    'kategori_urutan' => 3,
                    'urutan'          => $urutan + 1,
                    'status'          => 'draft',
                    'jenis'           => null,
                    'file'            => null,
                    'url'             => null,
                    'user_id'         => null,
                ]
            );
        }

        $count = PpidInformasi::setiapSaat()->where('is_fixed', true)->count();
        $this->command->info("✅ Seeded {$count} fixed items Informasi Setiap Saat.");
    }
}
