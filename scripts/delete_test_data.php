<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== Hapus data test ID:21 'BAB !' ===" . PHP_EOL;

// 1. Cek apakah ada ppid_informasi yang pakai id_jenis_dokumen = 21
$ppidTerkait = DB::table('ppid_informasi')->where('id_jenis_dokumen', 21)->get();
echo "  ppid_informasi terkait id_jenis_dokumen=21: " . count($ppidTerkait) . " baris" . PHP_EOL;
foreach ($ppidTerkait as $p) {
    echo "    → ppid_informasi ID:{$p->id} | {$p->nama_informasi} | status:{$p->status}" . PHP_EOL;
}

// 2. Hapus ppid_informasi terkait dulu (jika ada)
if ($ppidTerkait->count() > 0) {
    $del = DB::table('ppid_informasi')->where('id_jenis_dokumen', 21)->delete();
    echo "  ✅ Dihapus {$del} baris ppid_informasi terkait." . PHP_EOL;
} else {
    echo "  (tidak ada ppid_informasi terkait, lewati)" . PHP_EOL;
}

// 3. Hapus baris jenis_dokumen ID:21
$del2 = DB::table('jenis_dokumen')->where('id', 21)->delete();
if ($del2) {
    echo "  ✅ Baris jenis_dokumen ID:21 ('BAB !') berhasil dihapus." . PHP_EOL;
} else {
    echo "  ❌ Baris ID:21 tidak ditemukan atau gagal dihapus." . PHP_EOL;
}

// 4. Konfirmasi sisa data
$sisa = DB::table('jenis_dokumen')->orderBy('id')->get();
echo "\n  Sisa jenis_dokumen (" . count($sisa) . " baris):" . PHP_EOL;
foreach ($sisa as $s) {
    $grupVal = $s->grup ?? 'NULL';
    echo "    ID:{$s->id} | {$s->jenis_dokumen} | grup:{$grupVal}" . PHP_EOL;
}

echo "\n=== SELESAI ===" . PHP_EOL;
