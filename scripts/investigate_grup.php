<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// 1. Cek struktur kolom tabel jenis_dokumen (apakah kolom 'grup' sudah ada)
echo "\n=== [1] Struktur kolom tabel jenis_dokumen ===" . PHP_EOL;
$cols = DB::select("SHOW COLUMNS FROM jenis_dokumen");
foreach ($cols as $col) {
    echo "  Kolom: {$col->Field} | Type: {$col->Type} | Null: {$col->Null} | Default: {$col->Default}" . PHP_EOL;
}

// 2. Cek semua data di jenis_dokumen — termasuk kolom 'grup'
echo "\n=== [2] Semua data jenis_dokumen (termasuk kolom grup) ===" . PHP_EOL;
$data = DB::table('jenis_dokumen')->orderBy('id')->get();
echo "  Total baris: " . count($data) . PHP_EOL;
foreach ($data as $d) {
    $grupVal  = isset($d->grup) ? ($d->grup ?? 'NULL') : '(kolom tidak ada)';
    $grupFlag = (empty($d->grup)) ? ' <-- GRUP KOSONG/NULL!' : '';
    echo "  ID:{$d->id} | jenis_dokumen:{$d->jenis_dokumen} | klasifikasi:{$d->klasifikasi} | grup:{$grupVal} | status:{$d->status}{$grupFlag}" . PHP_EOL;
}

// 3. Cek data terbaru (id terbesar) — apakah baris baru atau menimpa lama
echo "\n=== [3] 3 baris terbaru (id terbesar) di jenis_dokumen ===" . PHP_EOL;
$terbaru = DB::table('jenis_dokumen')->orderByDesc('id')->limit(3)->get();
foreach ($terbaru as $t) {
    $grupVal = isset($t->grup) ? ($t->grup ?? 'NULL') : '(kolom tidak ada)';
    echo "  ID:{$t->id} | {$t->jenis_dokumen} | klasif:{$t->klasifikasi} | grup:{$grupVal} | created_at:{$t->created_at}" . PHP_EOL;
}

// 4. Simulasi: bagaimana controller membangun $grupKategori
echo "\n=== [4] Simulasi groupBy('grup') seperti di controller ===" . PHP_EOL;
$jenisDokumenList = collect(DB::table('jenis_dokumen')->where('status', '1')->orderBy('grup')->orderBy('jenis_dokumen')->get());
$grupKategori     = $jenisDokumenList->groupBy('grup');
echo "  Grup yang terbentuk:" . PHP_EOL;
foreach ($grupKategori as $grupNama => $items) {
    $label = ($grupNama === '') ? "(KOSONG/NULL — tidak akan masuk optgroup manapun!)" : $grupNama;
    echo "  Grup: [{$label}] — {$items->count()} item" . PHP_EOL;
    foreach ($items as $item) {
        echo "    → ID:{$item->id} | {$item->jenis_dokumen}" . PHP_EOL;
    }
}

// 5. Cek urutan grup hardcode di blade — mana saja yang terbentuk
echo "\n=== [5] Urutan grup yang dipakai blade (dari \$urutanGrup) ===" . PHP_EOL;
$urutanGrup = [
    'Informasi Berkala',
    'Informasi Serta Merta',
    'Informasi Setiap Saat',
    'Informasi Dikecualikan',
    'Laporan Akses Informasi',
    'Lainnya',
];
foreach ($urutanGrup as $g) {
    $ada = $grupKategori->has($g);
    echo "  " . ($ada ? "✅" : "❌") . " '{$g}'" . ($ada ? " ({$grupKategori[$g]->count()} item)" : " (tidak ada data)") . PHP_EOL;
}

$tidakTerdaftar = $grupKategori->keys()->diff($urutanGrup);
if ($tidakTerdaftar->isNotEmpty()) {
    echo "\n  !! Grup di DB yang TIDAK ada di \$urutanGrup (tidak akan tampil di dropdown):" . PHP_EOL;
    foreach ($tidakTerdaftar as $g) {
        echo "  ❌ '{$g}' — {$grupKategori[$g]->count()} item" . PHP_EOL;
    }
}

echo "\n=== SELESAI ===" . PHP_EOL;
