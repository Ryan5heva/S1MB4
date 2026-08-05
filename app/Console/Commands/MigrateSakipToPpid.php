<?php

namespace App\Console\Commands;

use App\Models\JenisDokumen;
use App\Models\PpidInformasi;
use App\Models\SakipRb;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateSakipToPpid extends Command
{
    /**
     * Nama dan signature command Artisan.
     *
     * Jalankan sekali secara manual setelah php artisan migrate:
     *   php artisan sakip:migrate-to-ppid
     *
     * Untuk uji coba tanpa benar-benar menyimpan data, gunakan --dry-run:
     *   php artisan sakip:migrate-to-ppid --dry-run
     */
    protected $signature = 'sakip:migrate-to-ppid
                            {--dry-run : Tampilkan preview tanpa menyimpan ke database}
                            {--force  : Jalankan meskipun sudah ada data SAKIP di ppid_informasi}';

    /**
     * Deskripsi command.
     */
    protected $description = 'Migrasi data dari tabel sakip_rbs ke ppid_informasi (kategori SAKIP)';

    public function handle(): int
    {
        $this->info('=== Migrasi Data SAKIP-RB → PPID ===');
        $this->newLine();

        // ── 1. Cari baris "SAKIP" di tabel jenis_dokumen ──────────────────
        $jenisDokumenSakip = JenisDokumen::where('jenis_dokumen', 'SAKIP')->first();

        if (! $jenisDokumenSakip) {
            $this->error('Baris "SAKIP" tidak ditemukan di tabel jenis_dokumen.');
            $this->line('Pastikan tabel jenis_dokumen sudah berisi baris dengan jenis_dokumen = "SAKIP".');
            return self::FAILURE;
        }

        $this->line('Kategori SAKIP ditemukan: id=' . $jenisDokumenSakip->id . ', klasifikasi=' . $jenisDokumenSakip->klasifikasi);
        $this->newLine();

        // ── 2. Cek apakah sudah ada data SAKIP di ppid_informasi ──────────
        $existing = PpidInformasi::where('id_jenis_dokumen', $jenisDokumenSakip->id)->count();

        if ($existing > 0 && ! $this->option('force')) {
            $this->warn('Sudah ada ' . $existing . ' baris data SAKIP di tabel ppid_informasi.');
            $this->warn('Gunakan --force untuk menjalankan ulang (data lama tidak dihapus, hanya ditambah).');
            $this->newLine();

            if (! $this->confirm('Lanjutkan migrasi?', false)) {
                $this->line('Migrasi dibatalkan.');
                return self::SUCCESS;
            }
        }

        // ── 3. Ambil semua data dari sakip_rbs ────────────────────────────
        $sakipList = SakipRb::orderBy('id')->get();

        if ($sakipList->isEmpty()) {
            $this->warn('Tabel sakip_rbs kosong. Tidak ada yang perlu dimigrasi.');
            return self::SUCCESS;
        }

        $this->line('Jumlah data di sakip_rbs : ' . $sakipList->count());
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('[DRY RUN] Data TIDAK akan disimpan ke database.');
        }

        $this->newLine();

        // ── 4. Tabel preview ──────────────────────────────────────────────
        $this->table(
            ['ID Sakip', 'Nama Informasi (dari jenis_dokumen)', 'Tahun', 'Jenis', 'Status Baru'],
            $sakipList->map(function ($s) {
                $jenis  = $s->file ? 'dokumen' : ($s->url ? 'link' : null);
                $status = $s->status === '1' ? 'publish' : 'draft';
                return [
                    $s->id,
                    str($s->jenis_dokumen)->limit(40),
                    $s->tahun,
                    $jenis ?? '(kosong)',
                    $status,
                ];
            })
        );

        $this->newLine();

        if ($dryRun) {
            $this->info('[DRY RUN] Preview selesai. Tidak ada data yang disimpan.');
            return self::SUCCESS;
        }

        // ── 5. Proses insert ──────────────────────────────────────────────
        $bar     = $this->output->createProgressBar($sakipList->count());
        $berhasil = 0;
        $gagal    = 0;

        DB::beginTransaction();

        try {
            foreach ($sakipList as $s) {
                // Tentukan jenis konten
                $jenis = null;
                if ($s->file) {
                    $jenis = 'dokumen';
                } elseif ($s->url) {
                    $jenis = 'link';
                }

                // Mapping status
                $status = ($s->status === '1') ? 'publish' : 'draft';

                PpidInformasi::create([
                    'jenis_menu'       => 'berkala',
                    'id_jenis_dokumen' => $jenisDokumenSakip->id,
                    'nama_informasi'   => $s->jenis_dokumen,
                    'deskripsi'        => $s->klasifikasi, // klasifikasi sebagai catatan tambahan
                    'jenis'            => $jenis,
                    'file'             => $s->file,
                    'url'              => $s->url,
                    'tahun'            => $s->tahun,
                    'status'           => $status,
                    'urutan'           => 0,
                    'is_fixed'         => false,
                    'user_id'          => $s->user_id,
                    // Kolom lama (cadangan) — isi dengan nilai default
                    'kategori'         => 'SAKIP',
                ]);

                $berhasil++;
                $bar->advance();
            }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            $bar->finish();
            $this->newLine(2);
            $this->error('Terjadi kesalahan saat migrasi: ' . $e->getMessage());
            $this->line($e->getTraceAsString());
            return self::FAILURE;
        }

        $bar->finish();
        $this->newLine(2);

        // ── 6. Ringkasan ──────────────────────────────────────────────────
        $this->info('✓ Migrasi selesai!');
        $this->table(
            ['Keterangan', 'Jumlah'],
            [
                ['Data di sakip_rbs',         $sakipList->count()],
                ['Berhasil dimigrasi ke PPID', $berhasil],
                ['Gagal',                       $gagal],
            ]
        );

        $this->newLine();
        $this->line('Langkah selanjutnya:');
        $this->line('  1. Buka halaman PPID di browser');
        $this->line('  2. Pilih kategori "SAKIP" dari dropdown');
        $this->line('  3. Verifikasi data tampil dengan benar');
        $this->line('  4. Jika sudah yakin, tabel sakip_rbs dapat dihapus secara manual');

        return self::SUCCESS;
    }
}
