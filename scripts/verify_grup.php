<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = DB::select('SELECT id, jenis_dokumen, grup FROM jenis_dokumen ORDER BY grup, jenis_dokumen');
foreach ($rows as $r) {
    printf("%-4s %-55s %s\n", $r->id, $r->jenis_dokumen, $r->grup);
}
