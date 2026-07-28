<?php

namespace Database\Seeders;

use App\Models\PpidInformasi;
use Illuminate\Database\Seeder;

/**
 * Seed semua baris Informasi Berkala yang bersifat TETAP.
 *
 * Data ini sesuai ketentuan UU No. 14/2008 tentang Keterbukaan Informasi Publik.
 * Admin TIDAK BOLEH menambah, mengubah, atau menghapus baris ini.
 * Admin hanya mengelola dokumen/link pada setiap baris (via edit).
 *
 * Seeder ini menggunakan updateOrCreate sehingga aman dijalankan berulang kali
 * tanpa membuat data ganda.
 */
class PpidInformasiBerkalaSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            // ─────────────────────────────────────────────────────────────
            // 1. Profil Badan Publik
            // ─────────────────────────────────────────────────────────────
            [
                'kategori'         => 'Profil Badan Publik',
                'kategori_urutan'  => 1,
                'items'            => [
                    'Kedudukan, Domisili dan Alamat Lengkap',
                    'Tujuan dan Sasaran',
                    'Tugas dan Fungsi',
                    'Struktur Organisasi',
                    'Profil Pejabat Struktural',
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            // 2. Program dan/atau Kegiatan
            // ─────────────────────────────────────────────────────────────
            [
                'kategori'         => 'Program dan/atau Kegiatan',
                'kategori_urutan'  => 2,
                'items'            => [
                    'Nama Program dan Kegiatan',
                    'Penanggung Jawab dan Pelaksana Program',
                    'Target dan Capaian Program/Kegiatan',
                    'Jadwal Pelaksanaan Program/Kegiatan',
                    'Nilai Anggaran Kegiatan Per Program',
                    'Agenda Penting Program/Kegiatan',
                    'Informasi Khusus lainnya yang berkaitan langsung dengan hak-hak masyarakat',
                    'Informasi tentang Rekrutmen Pegawai non-PNS',
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            // 3. Kinerja Badan Publik
            // ─────────────────────────────────────────────────────────────
            [
                'kategori'         => 'Kinerja Badan Publik',
                'kategori_urutan'  => 3,
                'items'            => [
                    'Penilaian Kinerja',
                    'Efisiensi yang dicapai',
                    'Laporan seluruh Program dan Kegiatan yang telah dijalankan',
                    'Laporan Umum dan Keuangan',
                    'Target dan Penyerapan Kegiatan',
                    'Laporan Keterangan Pertanggungjawaban (LKPJ)',
                    'Ringkasan Kinerja/Kegiatan',
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            // 4. Laporan Keuangan
            // ─────────────────────────────────────────────────────────────
            [
                'kategori'         => 'Laporan Keuangan',
                'kategori_urutan'  => 4,
                'items'            => [
                    'Rencana dan Realisasi Anggaran',
                    'Neraca',
                    'Laporan Arus Kas dan Catatan atas Laporan Keuangan',
                    'Daftar Aset dan Investasi',
                    'RKA',
                    'DPA',
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            // 5. Laporan Akses Informasi Publik
            // ─────────────────────────────────────────────────────────────
            [
                'kategori'         => 'Laporan Akses Informasi Publik',
                'kategori_urutan'  => 5,
                'items'            => [
                    'Jumlah Permintaan Informasi Publik yang diterima',
                    'Waktu yang diperlukan dalam memenuhi setiap Permintaan Informasi Publik',
                    'Jumlah Permintaan Informasi Publik yang dikabulkan sebagian atau seluruhnya dan Permintaan Informasi Publik yang ditolak',
                    'Alasan Penolakan Permintaan Informasi Publik',
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            // 6. Prosedur Permohonan Informasi Publik
            // ─────────────────────────────────────────────────────────────
            [
                'kategori'         => 'Prosedur Permohonan Informasi Publik',
                'kategori_urutan'  => 6,
                'items'            => [
                    'Tata Cara Memperoleh Informasi Publik',
                    'Tata Cara Pengajuan Keberatan dan Proses Penyelesaian Sengketa Informasi Publik',
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            // 7. Tata Cara Pengaduan Penyalahgunaan Wewenang
            // ─────────────────────────────────────────────────────────────
            [
                'kategori'         => 'Tata Cara Pengaduan Penyalahgunaan Wewenang atau Pelanggaran Badan Publik',
                'kategori_urutan'  => 7,
                'items'            => [
                    'LAPOR JATIM',
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            // 8. Pengadaan Barang dan Jasa Pemerintah
            // ─────────────────────────────────────────────────────────────
            [
                'kategori'         => 'Pengadaan Barang dan Jasa Pemerintah',
                'kategori_urutan'  => 8,
                'items'            => [
                    'LPSE Jatim',
                    'SIRUP Jatim',
                ],
            ],

            // ─────────────────────────────────────────────────────────────
            // 9. Ketenagakerjaan — tabel kosong, admin isi sendiri
            //    (is_fixed = false, tidak di-seed di sini)
            // ─────────────────────────────────────────────────────────────

            // ─────────────────────────────────────────────────────────────
            // 10. Prosedur Peringatan Dini dan Evakuasi Keadaan Darurat
            // ─────────────────────────────────────────────────────────────
            [
                'kategori'         => 'Prosedur Peringatan Dini dan Prosedur Evakuasi Keadaan Darurat',
                'kategori_urutan'  => 10,
                'items'            => [
                    'BMKG',
                    'Prosedur Peringatan Dini dan Evakuasi',
                ],
            ],
        ];

        foreach ($sections as $section) {
            foreach ($section['items'] as $urutan => $namaInformasi) {
                PpidInformasi::updateOrCreate(
                    [
                        'jenis_menu'     => 'berkala',
                        'kategori'       => $section['kategori'],
                        'nama_informasi' => $namaInformasi,
                        'is_fixed'       => true,
                    ],
                    [
                        'kategori_urutan' => $section['kategori_urutan'],
                        'urutan'          => $urutan + 1,
                        'status'          => 'draft',
                        'jenis'           => null,
                        'file'            => null,
                        'url'             => null,
                        'user_id'         => null,
                    ]
                );
            }
        }

        $this->command->info('✅ Seeded ' . PpidInformasi::berkala()->where('is_fixed', true)->count() . ' fixed items Informasi Berkala.');
    }
}
