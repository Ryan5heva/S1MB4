<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

/**
 * Logika resolveGrup yang sama persis dengan JenisDokumenController::resolveGrup()
 */
function resolveGrup(string $klasifikasi): string
{
    $k = strtolower(trim($klasifikasi));

    if (str_contains($k, 'berkala'))         return 'Informasi Berkala';
    if (str_contains($k, 'serta merta'))     return 'Informasi Serta Merta';
    if (str_contains($k, 'setiap saat'))     return 'Informasi Setiap Saat';
    if (str_contains($k, 'dikecualikan'))    return 'Informasi Dikecualikan';
    if (str_contains($k, 'laporan akses'))   return 'Laporan Akses Informasi';

    return 'Lainnya';
}

echo "\n=== Update kolom 'grup' untuk semua baris di jenis_dokumen ===" . PHP_EOL;

$semua = DB::table('jenis_dokumen')->orderBy('id')->get();
$updated = 0;
$unchanged = 0;

foreach ($semua as $row) {
    $newGrup = resolveGrup($row->klasifikasi);
    $oldGrup = $row->grup ?? 'NULL';

    if ($newGrup === $oldGrup) {
        echo "  ID:{$row->id} | '{$row->klasifikasi}' → '{$newGrup}' (tidak berubah)" . PHP_EOL;
        $unchanged++;
    } else {
        DB::table('jenis_dokumen')->where('id', $row->id)->update(['grup' => $newGrup]);
        echo "  ID:{$row->id} | '{$row->klasifikasi}' → '{$oldGrup}' ➜ '{$newGrup}' ✅ diupdate" . PHP_EOL;
        $updated++;
    }
}

echo PHP_EOL;
echo "  Total: {$updated} diupdate, {$unchanged} tidak berubah." . PHP_EOL;

echo "\n=== Hasil akhir semua baris ===" . PHP_EOL;
$hasil = DB::table('jenis_dokumen')->orderBy('id')->get();
foreach ($hasil as $h) {
    echo "  ID:{$h->id} | {$h->jenis_dokumen} | klasifikasi:{$h->klasifikasi} | grup:{$h->grup}" . PHP_EOL;
}

echo "\n=== SELESAI ===" . PHP_EOL;
