<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// ============================================================
// 1. Semua data di tabel jenis_dokumen
// ============================================================
echo "\n=== [1] TABEL jenis_dokumen ===" . PHP_EOL;
$jenis = DB::table('jenis_dokumen')
    ->select('id', 'jenis_dokumen', 'klasifikasi', 'status')
    ->orderBy('id')
    ->get();
foreach ($jenis as $j) {
    echo "  ID:{$j->id} | jenis_dokumen: {$j->jenis_dokumen} | klasifikasi: {$j->klasifikasi} | status: {$j->status}" . PHP_EOL;
}

// ============================================================
// 2. Data ppid_informasi dengan jenis_menu = berkala (join ke jenis_dokumen)
// ============================================================
echo "\n=== [2] ppid_informasi WHERE jenis_menu='berkala' (join jenis_dokumen) ===" . PHP_EOL;
$berkala = DB::table('ppid_informasi as p')
    ->leftJoin('jenis_dokumen as j', 'p.id_jenis_dokumen', '=', 'j.id')
    ->select('p.id', 'p.nama_informasi', 'p.jenis_menu', 'p.id_jenis_dokumen', 'p.status', 'j.jenis_dokumen', 'j.klasifikasi')
    ->where('p.jenis_menu', 'berkala')
    ->orderBy('p.id')
    ->get();
if ($berkala->isEmpty()) {
    echo "  (tidak ada data berkala)" . PHP_EOL;
} else {
    foreach ($berkala as $b) {
        echo "  ID:{$b->id} | nama_informasi:{$b->nama_informasi} | jenis_menu:{$b->jenis_menu} | id_jenis_dok:{$b->id_jenis_dokumen} | jenis_dok:{$b->jenis_dokumen} | klasifikasi_dok:{$b->klasifikasi} | status:{$b->status}" . PHP_EOL;
    }
}

// ============================================================
// 3. MISMATCH: jenis_dokumen.klasifikasi = 'sakip' tapi ppid_informasi.jenis_menu = 'berkala'
// ============================================================
echo "\n=== [3] MISMATCH: jenis_dok.klasifikasi='sakip' tapi jenis_menu='berkala' ===" . PHP_EOL;
$mismatch = DB::table('ppid_informasi as p')
    ->join('jenis_dokumen as j', 'p.id_jenis_dokumen', '=', 'j.id')
    ->select('p.id', 'p.nama_informasi', 'p.jenis_menu', 'p.id_jenis_dokumen', 'j.jenis_dokumen', 'j.klasifikasi', 'p.status')
    ->where('j.klasifikasi', 'sakip')
    ->where('p.jenis_menu', '!=', 'sakip')
    ->get();
if ($mismatch->isEmpty()) {
    echo "  Tidak ada mismatch sakip-vs-berkala di ppid_informasi." . PHP_EOL;
} else {
    echo "  !! ADA MISMATCH !!" . PHP_EOL;
    foreach ($mismatch as $m) {
        echo "  ID:{$m->id} | nama:{$m->nama_informasi} | jenis_menu:{$m->jenis_menu} | id_jenis_dok:{$m->id_jenis_dokumen} | jenis_dok:{$m->jenis_dokumen} | klasifikasi:{$m->klasifikasi}" . PHP_EOL;
    }
}

// ============================================================
// 4. Cek apakah query scope berkala() filter berdasarkan jenis_menu saja (tanpa filter klasifikasi jenis_dokumen)
//    → ini mengungkap semua baris yang LOLOS filter scope berkala
// ============================================================
echo "\n=== [4] Semua ppid_informasi jenis_menu='berkala' (FULL detail untuk validasi) ===" . PHP_EOL;
$allBerkala = DB::table('ppid_informasi as p')
    ->leftJoin('jenis_dokumen as j', 'p.id_jenis_dokumen', '=', 'j.id')
    ->select('p.id', 'p.nama_informasi', 'p.jenis_menu', 'p.id_jenis_dokumen', 'j.id as j_id', 'j.jenis_dokumen', 'j.klasifikasi', 'p.status')
    ->where('p.jenis_menu', 'berkala')
    ->orderBy('p.id')
    ->get();
echo "  Total baris: " . count($allBerkala) . PHP_EOL;
foreach ($allBerkala as $b) {
    $flag = ($b->klasifikasi && strtolower($b->klasifikasi) !== 'berkala') ? ' <-- MISMATCH KLASIFIKASI!' : '';
    echo "  ID:{$b->id} | {$b->nama_informasi} | id_jd:{$b->id_jenis_dokumen} | jenis_dok:{$b->jenis_dokumen} | klasifikasi:{$b->klasifikasi} | status:{$b->status}{$flag}" . PHP_EOL;
}

// ============================================================
// 5. Lihat SEMUA ppid_informasi + jenis_dokumen (untuk melihat data keseluruhan)
// ============================================================
echo "\n=== [5] SEMUA ppid_informasi (lengkap) ===" . PHP_EOL;
$all = DB::table('ppid_informasi as p')
    ->leftJoin('jenis_dokumen as j', 'p.id_jenis_dokumen', '=', 'j.id')
    ->select('p.id', 'p.nama_informasi', 'p.jenis_menu', 'p.id_jenis_dokumen', 'j.jenis_dokumen', 'j.klasifikasi', 'p.status')
    ->orderBy('p.jenis_menu')
    ->orderBy('p.id')
    ->get();
echo "  Total baris: " . count($all) . PHP_EOL;
foreach ($all as $a) {
    echo "  ID:{$a->id} | jenis_menu:{$a->jenis_menu} | id_jd:{$a->id_jenis_dokumen} | jenis_dok:{$a->jenis_dokumen} | klasif:{$a->klasifikasi} | nama:{$a->nama_informasi} | status:{$a->status}" . PHP_EOL;
}

echo "\n=== SELESAI ===" . PHP_EOL;
