<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\PpidInformasiBerkalaController;
use App\Http\Controllers\PpidInformasiSertaMertaController;
use App\Http\Controllers\PpidInformasiSetiapSaatController;
use App\Http\Controllers\PpidInformasiDikecualikanController;
use App\Http\Controllers\PpidLaporanAksesInformasiController;
use App\Http\Controllers\SakipRbController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\JenisDokumenController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Berita CRUD
    Route::resource('berita', BeritaController::class)->parameters([
        'berita' => 'beritum',
    ]);

    // Berita Preview
    Route::get('/berita/{beritum}/preview', [BeritaController::class, 'preview'])->name('berita.preview');

    // User CRUD
    Route::resource('users', UserController::class);

    // Video CRUD
    Route::resource('video', VideoController::class);

    // Riwayat Aktivitas
    Route::get('/riwayat', [ActivityLogController::class, 'index'])->name('riwayat.index');
    Route::delete('/riwayat/{activityLog}', [ActivityLogController::class, 'destroy'])->name('riwayat.destroy');

    // Setting — gabungan Riwayat Aktivitas + Kelola Pengguna
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

    // -------------------------------------------------------------------------
    // PPID — Pengelolaan Informasi Publik
    // -------------------------------------------------------------------------

    // Informasi Berkala — jadi halaman utama /ppid
    Route::resource('ppid', PpidInformasiBerkalaController::class)
        ->except(['show'])
        ->names('ppid.berkala')
        ->parameters(['ppid' => 'ppid']);

    // Informasi Serta Merta (index, edit, update — semua perihal fixed)
    Route::resource('ppid/serta-merta', PpidInformasiSertaMertaController::class)
        ->only(['index', 'edit', 'update'])
        ->names('ppid.serta_merta')
        ->parameters(['serta-merta' => 'ppid']);

    // Informasi Setiap Saat (index, edit, update — semua perihal fixed)
    Route::resource('ppid/setiap-saat', PpidInformasiSetiapSaatController::class)
        ->only(['index', 'edit', 'update'])
        ->names('ppid.setiap_saat')
        ->parameters(['setiap-saat' => 'ppid']);

    // Informasi Dikecualikan (index, edit, update — semua perihal fixed)
    Route::resource('ppid/dikecualikan', PpidInformasiDikecualikanController::class)
        ->only(['index', 'edit', 'update'])
        ->names('ppid.dikecualikan')
        ->parameters(['dikecualikan' => 'ppid']);

    // Laporan Akses Informasi (index, edit, update — semua perihal fixed)
    Route::resource('ppid/laporan-akses-informasi', PpidLaporanAksesInformasiController::class)
        ->only(['index', 'edit', 'update'])
        ->names('ppid.laporan_akses_informasi')
        ->parameters(['laporan-akses-informasi' => 'ppid']);

    // -------------------------------------------------------------------------
    // SAKIP-RB — Sistem Akuntabilitas Kinerja Instansi Pemerintah & Reformasi Birokrasi
    // -------------------------------------------------------------------------
    Route::resource('sakip-rb', SakipRbController::class)
        ->parameters(['sakip-rb' => 'sakipRb']);

    // -------------------------------------------------------------------------
    // Jenis Dokumen — Master data kategori dokumen PPID
    // -------------------------------------------------------------------------
    Route::resource('jenis-dokumen', JenisDokumenController::class)
        ->except(['show'])
        ->parameters(['jenis-dokumen' => 'jenis_dokumen']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});