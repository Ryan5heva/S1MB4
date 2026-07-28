<?php

namespace Database\Seeders;

use App\Models\PpidInformasi;
use Illuminate\Database\Seeder;

/**
 * Seed 1 baris Informasi Dikecualikan yang bersifat TETAP (fixed).
 *
 * Sesuai ketentuan UU No. 14/2008, informasi dikecualikan adalah
 * informasi yang tidak dapat diakses oleh pemohon informasi publik.
 *
 * Admin TIDAK boleh menambah, menghapus, atau mengubah nama Perihal.
 * Admin hanya mengelola dokumen/link pada setiap Perihal.
 */
class PpidInformasiDikecualikanSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Klasifikasi Informasi yang Dikecualikan',
        ];

        foreach ($items as $urutan => $perihal) {
            PpidInformasi::updateOrCreate(
                [
                    'jenis_menu'     => 'dikecualikan',
                    'nama_informasi' => $perihal,
                    'is_fixed'       => true,
                ],
                [
                    'kategori'        => 'Informasi Dikecualikan',
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

        $count = PpidInformasi::dikecualikan()->where('is_fixed', true)->count();
        $this->command->info("✅ Seeded {$count} fixed items Informasi Dikecualikan.");
    }
}
