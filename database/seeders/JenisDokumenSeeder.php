<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_dokumen')->insert([
            ['id' => 1,  'jenis_dokumen' => 'Profil Badan Publik', 'status' => 1, 'klasifikasi' => 'Berkala'],
            ['id' => 2,  'jenis_dokumen' => 'Program dan/atau Kegiatan', 'status' => 1, 'klasifikasi' => 'Berkala'],
            ['id' => 3,  'jenis_dokumen' => 'Kinerja Badan Publik', 'status' => 1, 'klasifikasi' => 'Berkala'],
            ['id' => 4,  'jenis_dokumen' => 'Laporan Keuangan', 'status' => 1, 'klasifikasi' => 'Berkala'],
            ['id' => 5,  'jenis_dokumen' => 'Laporan Akses Informasi Publik', 'status' => 1, 'klasifikasi' => 'Berkala'],
            ['id' => 6,  'jenis_dokumen' => 'Prosedur Permohonan Informasi Publik', 'status' => 1, 'klasifikasi' => 'Berkala'],
            ['id' => 7,  'jenis_dokumen' => 'Tata Cara Pengaduan Penyalahgunaan Wewenang atau Pelanggaran oleh Badan Publik yang Bersangkutan atau Pihak Lain', 'status' => 1, 'klasifikasi' => 'Berkala'], // CEK ULANG teks lengkap
            ['id' => 8,  'jenis_dokumen' => 'Pengadaan Barang dan Jasa Pemerintah', 'status' => 1, 'klasifikasi' => 'Berkala'],
            ['id' => 9,  'jenis_dokumen' => 'Ketenagakerjaan', 'status' => 1, 'klasifikasi' => 'Berkala'],
            ['id' => 10, 'jenis_dokumen' => 'Prosedur Peringatan Dini dan Prosedur Evakuasi Keadaan Darurat', 'status' => 1, 'klasifikasi' => 'Berkala'], // CEK ULANG teks lengkap
            ['id' => 11, 'jenis_dokumen' => 'SK PPID', 'status' => 1, 'klasifikasi' => 'Dokumen'],
            ['id' => 12, 'jenis_dokumen' => 'Daftar Informasi Publik', 'status' => 1, 'klasifikasi' => 'Dokumen'],
            ['id' => 13, 'jenis_dokumen' => 'Laporan SKM', 'status' => 1, 'klasifikasi' => 'Dokumen'],
            ['id' => 14, 'jenis_dokumen' => 'SAKIP', 'status' => 1, 'klasifikasi' => 'sakip'],
        ]);
    }
}