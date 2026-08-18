<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== Semua data jenis_dokumen (lengkap dengan grup) ===" . PHP_EOL;
$data = DB::table('jenis_dokumen')->orderBy('id')->get();
echo str_pad("ID", 5) . str_pad("klasifikasi", 30) . str_pad("grup", 30) . "jenis_dokumen" . PHP_EOL;
echo str_repeat("-", 100) . PHP_EOL;
foreach ($data as $d) {
    $grup = $d->grup ?? 'NULL';
    echo str_pad($d->id, 5)
       . str_pad($d->klasifikasi, 30)
       . str_pad($grup, 30)
       . $d->jenis_dokumen . PHP_EOL;
}

echo "\n=== Distinct nilai klasifikasi ===" . PHP_EOL;
$distinct = DB::table('jenis_dokumen')->select('klasifikasi')->distinct()->orderBy('klasifikasi')->get();
foreach ($distinct as $d) {
    $count = DB::table('jenis_dokumen')->where('klasifikasi', $d->klasifikasi)->count();
    echo "  '{$d->klasifikasi}' ({$count} baris)" . PHP_EOL;
}

echo "\n=== Kasus 'Dokumen': grup berbeda-beda ===" . PHP_EOL;
$dokumen = DB::table('jenis_dokumen')->where('klasifikasi', 'Dokumen')->get();
foreach ($dokumen as $d) {
    echo "  ID:{$d->id} | {$d->jenis_dokumen} | grup:{$d->grup}" . PHP_EOL;
}
echo "  ⚠️  'Dokumen' punya 3 baris dengan grup BERBEDA — tidak bisa di-auto-map dari klasifikasi saja!" . PHP_EOL;

echo "\n=== SELESAI ===" . PHP_EOL;
