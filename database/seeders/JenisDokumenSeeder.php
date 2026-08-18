<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Menggunakan upsert() agar aman dijalankan ulang tanpa duplikasi.
     * Kolom 'grup' digunakan untuk pengelompokan <optgroup> di dropdown admin.
     *
     * Pengelompokan:
     *   - id 1–10  → 'Informasi Berkala'
     *   - id 12    → 'Informasi Setiap Saat'  (Daftar Informasi Publik, sesuai UU KIP Pasal 11)
     *   - id 11, 13, 14 → 'Lainnya'
     */
    public function run(): void
    {
        DB::table('jenis_dokumen')->upsert([
            ['id' => 1,  'jenis_dokumen' => 'Profil Badan Publik',                                                                              'status' => 1, 'klasifikasi' => 'Berkala', 'grup' => 'Informasi Berkala'],
            ['id' => 2,  'jenis_dokumen' => 'Program dan/atau Kegiatan',                                                                        'status' => 1, 'klasifikasi' => 'Berkala', 'grup' => 'Informasi Berkala'],
            ['id' => 3,  'jenis_dokumen' => 'Kinerja Badan Publik',                                                                             'status' => 1, 'klasifikasi' => 'Berkala', 'grup' => 'Informasi Berkala'],
            ['id' => 4,  'jenis_dokumen' => 'Laporan Keuangan',                                                                                 'status' => 1, 'klasifikasi' => 'Berkala', 'grup' => 'Informasi Berkala'],
            ['id' => 5,  'jenis_dokumen' => 'Laporan Akses Informasi Publik',                                                                   'status' => 1, 'klasifikasi' => 'Berkala', 'grup' => 'Informasi Berkala'],
            ['id' => 6,  'jenis_dokumen' => 'Prosedur Permohonan Informasi Publik',                                                             'status' => 1, 'klasifikasi' => 'Berkala', 'grup' => 'Informasi Berkala'],
            ['id' => 7,  'jenis_dokumen' => 'Tata Cara Pengaduan Penyalahgunaan Wewenang atau Pelanggaran oleh Badan Publik yang Bersangkutan atau Pihak Lain', 'status' => 1, 'klasifikasi' => 'Berkala', 'grup' => 'Informasi Berkala'],
            ['id' => 8,  'jenis_dokumen' => 'Pengadaan Barang dan Jasa Pemerintah',                                                             'status' => 1, 'klasifikasi' => 'Berkala', 'grup' => 'Informasi Berkala'],
            ['id' => 9,  'jenis_dokumen' => 'Ketenagakerjaan',                                                                                  'status' => 1, 'klasifikasi' => 'Berkala', 'grup' => 'Informasi Berkala'],
            ['id' => 10, 'jenis_dokumen' => 'Prosedur Peringatan Dini dan Prosedur Evakuasi Keadaan Darurat',                                   'status' => 1, 'klasifikasi' => 'Berkala', 'grup' => 'Informasi Berkala'],
            ['id' => 11, 'jenis_dokumen' => 'SK PPID',                                                                                          'status' => 1, 'klasifikasi' => 'Dokumen', 'grup' => 'Lainnya'],
            ['id' => 12, 'jenis_dokumen' => 'Daftar Informasi Publik',                                                                          'status' => 1, 'klasifikasi' => 'Dokumen', 'grup' => 'Informasi Setiap Saat'],
            ['id' => 13, 'jenis_dokumen' => 'Laporan SKM',                                                                                      'status' => 1, 'klasifikasi' => 'Dokumen', 'grup' => 'Lainnya'],
            ['id' => 14, 'jenis_dokumen' => 'SAKIP',                                                                                            'status' => 1, 'klasifikasi' => 'sakip',   'grup' => 'Lainnya'],
        ], uniqueBy: ['id'], update: ['jenis_dokumen', 'status', 'klasifikasi', 'grup']);
    }
}