<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PpidInformasi;
use App\Models\JenisDokumen;

echo "\n=== [A] scope berkala() SETELAH perbaikan ===" . PHP_EOL;
echo "Harapan: ID:55 (SAKIP) TIDAK muncul, hanya ID:1-37 (NULL) dan ID:56-59 (Berkala)\n" . PHP_EOL;

$items = PpidInformasi::berkala()
    ->published()
    ->with('jenisDokumen')
    ->orderBy('id')
    ->get();

echo "Total item berkala published: " . count($items) . PHP_EOL;
foreach ($items as $item) {
    $klas = $item->jenisDokumen?->klasifikasi ?? '(NULL)';
    $jd   = $item->jenisDokumen?->jenis_dokumen ?? '(NULL)';
    $flag = ($klas !== '(NULL)' && strtolower($klas) !== 'berkala') ? ' <-- MASIH MUNCUL! BUG!' : '';
    echo "  ID:{$item->id} | {$item->nama_informasi} | jenis_dok:{$jd} | klasifikasi:{$klas}{$flag}" . PHP_EOL;
}

echo PHP_EOL;
echo "=== [B] Verifikasi: tidak ada klasifikasi selain Berkala/NULL ===" . PHP_EOL;
$klasList = $items->map(fn($i) => $i->jenisDokumen?->klasifikasi ?? 'NULL')->unique()->values();
echo "Klasifikasi yang muncul: " . $klasList->implode(', ') . PHP_EOL;
if ($klasList->filter(fn($k) => $k !== 'NULL' && strtolower($k) !== 'berkala')->count() === 0) {
    echo "✅ LULUS — tidak ada data SAKIP/lainnya yang bocor ke Berkala." . PHP_EOL;
} else {
    echo "❌ GAGAL — masih ada data non-Berkala yang muncul!" . PHP_EOL;
}

echo PHP_EOL;
echo "=== [C] Verifikasi dropdown jenisDokumenList (semua aktif, termasuk SAKIP) ===" . PHP_EOL;
$dropdown = JenisDokumen::where('status', '1')->orderBy('klasifikasi')->orderBy('jenis_dokumen')->get();
echo "Total pilihan di dropdown: " . count($dropdown) . PHP_EOL;
$hasSakip = false;
foreach ($dropdown as $jd) {
    $sakipFlag = strtolower($jd->klasifikasi) === 'sakip' ? ' ← SAKIP tersedia ✅' : '';
    echo "  ID:{$jd->id} | {$jd->jenis_dokumen} | klasifikasi:{$jd->klasifikasi}{$sakipFlag}" . PHP_EOL;
    if (strtolower($jd->klasifikasi) === 'sakip') $hasSakip = true;
}
if ($hasSakip) {
    echo "\n✅ SAKIP tersedia di dropdown." . PHP_EOL;
} else {
    echo "\n❌ SAKIP tidak ditemukan di dropdown!" . PHP_EOL;
}

echo "\n=== SELESAI ===" . PHP_EOL;
