<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

echo "\n=== Verifikasi Rule Validasi Baru ===" . PHP_EOL;

// Test 1: id=14 (SAKIP, status=1) — harus LULUS
$v1 = Validator::make(
    ['id_jenis_dokumen' => 14],
    ['id_jenis_dokumen' => ['required', 'integer', Rule::exists('jenis_dokumen', 'id')->where('status', '1')]]
);
echo "id=14 (SAKIP, aktif): " . ($v1->passes() ? '✅ LULUS' : '❌ GAGAL — ' . $v1->errors()->first('id_jenis_dokumen')) . PHP_EOL;

// Test 2: id=1 (Profil Badan Publik Berkala, status=1) — harus LULUS
$v2 = Validator::make(
    ['id_jenis_dokumen' => 1],
    ['id_jenis_dokumen' => ['required', 'integer', Rule::exists('jenis_dokumen', 'id')->where('status', '1')]]
);
echo "id=1  (Profil Badan Publik, aktif): " . ($v2->passes() ? '✅ LULUS' : '❌ GAGAL — ' . $v2->errors()->first('id_jenis_dokumen')) . PHP_EOL;

// Test 3: id=999 (tidak ada) — harus GAGAL
$v3 = Validator::make(
    ['id_jenis_dokumen' => 999],
    ['id_jenis_dokumen' => ['required', 'integer', Rule::exists('jenis_dokumen', 'id')->where('status', '1')]]
);
echo "id=999 (tidak ada): " . ($v3->passes() ? '❌ HARUSNYA GAGAL!' : '✅ Ditolak dengan benar — ' . $v3->errors()->first('id_jenis_dokumen')) . PHP_EOL;

// Test 4: Pastikan rule LAMA (klasifikasi=Berkala) memang menolak SAKIP
$v4 = Validator::make(
    ['id_jenis_dokumen' => 14],
    ['id_jenis_dokumen' => ['required', 'integer', Rule::exists('jenis_dokumen', 'id')->where('klasifikasi', 'Berkala')]]
);
echo "id=14 (SAKIP) vs rule LAMA: " . ($v4->passes() ? '❌ LULUS (bug lama)' : '✅ Ditolak — konfirmasi bug lama terbukti: ' . $v4->errors()->first('id_jenis_dokumen')) . PHP_EOL;

echo "\n=== SELESAI ===" . PHP_EOL;
