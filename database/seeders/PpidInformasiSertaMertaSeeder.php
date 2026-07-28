<?php

namespace Database\Seeders;

use App\Models\PpidInformasi;
use Illuminate\Database\Seeder;

/**
 * Seed 5 baris Informasi Serta Merta yang bersifat TETAP.
 *
 * Sesuai ketentuan UU No. 14/2008, informasi serta merta adalah informasi
 * yang dapat mengancam hajat hidup orang banyak dan ketertiban umum —
 * wajib diumumkan tanpa penundaan.
 *
 * Admin TIDAK boleh menambah, menghapus, atau mengubah daftar Perihal ini.
 * Admin hanya mengelola dokumen/link pada setiap Perihal.
 */
class PpidInformasiSertaMertaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Informasi mengenai prosedur peringatan dini dan prosedur evakuasi keadaan darurat saat terjadi kebakaran',
            'Informasi mengenai prosedur peringatan dini dan prosedur evakuasi keadaan darurat saat terjadi gempa bumi',
            'Informasi Mengenai Bencana Alam',
            'Informasi Mengenai Gempa Bumi dan Tsunami',
            'Informasi Tentang Virus dan Penanggulangannya',
        ];

        foreach ($items as $urutan => $perihal) {
            PpidInformasi::updateOrCreate(
                [
                    'jenis_menu'     => 'serta_merta',
                    'nama_informasi' => $perihal,
                    'is_fixed'       => true,
                ],
                [
                    'kategori'        => 'Informasi Serta Merta',
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

        $count = PpidInformasi::sertaMerta()->where('is_fixed', true)->count();
        $this->command->info("✅ Seeded {$count} fixed items Informasi Serta Merta.");
    }
}
