<?php

namespace Database\Seeders;

use App\Models\PpidKlasifikasi;
use Illuminate\Database\Seeder;

/**
 * Seed 5 baris klasifikasi PPID awal.
 *
 * Data ini mencerminkan 5 klasifikasi berdasarkan UU No. 14/2008
 * tentang Keterbukaan Informasi Publik.
 *
 * Kolom jenis_menu_key harus cocok persis dengan nilai ENUM
 * pada kolom jenis_menu di tabel ppid_informasi.
 *
 * Aman dijalankan berulang kali (updateOrCreate berdasarkan jenis_menu_key).
 */
class PpidKlasifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $klasifikasi = [
            [
                'nama_tampil'    => 'Informasi Berkala',
                'slug_url'       => '/ppid/berkala',
                'jenis_menu_key' => 'berkala',
                'urutan'         => 1,
                'aktif'          => true,
            ],
            [
                'nama_tampil'    => 'Informasi Serta Merta',
                'slug_url'       => '/ppid/serta-merta',
                'jenis_menu_key' => 'serta_merta',
                'urutan'         => 2,
                'aktif'          => true,
            ],
            [
                'nama_tampil'    => 'Informasi Setiap Saat',
                'slug_url'       => '/ppid/setiap-saat',
                'jenis_menu_key' => 'setiap_saat',
                'urutan'         => 3,
                'aktif'          => true,
            ],
            [
                'nama_tampil'    => 'Informasi Dikecualikan',
                'slug_url'       => '/ppid/dikecualikan',
                'jenis_menu_key' => 'dikecualikan',
                'urutan'         => 4,
                'aktif'          => true,
            ],
            [
                'nama_tampil'    => 'Laporan Akses Informasi',
                'slug_url'       => '/ppid/laporan-akses-informasi',
                'jenis_menu_key' => 'laporan_akses_informasi',
                'urutan'         => 5,
                'aktif'          => true,
            ],
        ];

        foreach ($klasifikasi as $data) {
            PpidKlasifikasi::updateOrCreate(
                ['jenis_menu_key' => $data['jenis_menu_key']],
                $data
            );
        }

        $count = PpidKlasifikasi::count();
        $this->command->info("✅ Seeded {$count} klasifikasi PPID.");
    }
}
