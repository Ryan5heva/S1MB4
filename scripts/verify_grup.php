<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = DB::select('SELECT id, jenis_dokumen, klasifikasi, grup, status, created_at, updated_at FROM jenis_dokumen ORDER BY id');
foreach ($rows as $r) {
    printf(
        "id=%-3s | %-65s | klas=%-20s | grup=%-25s | status=%s | created=%s | updated=%s\n",
        $r->id,
        $r->jenis_dokumen,
        $r->klasifikasi,
        $r->grup ?? '(null)',
        $r->status,
        $r->created_at ?? '(null)',
        $r->updated_at ?? '(null)'
    );
}
