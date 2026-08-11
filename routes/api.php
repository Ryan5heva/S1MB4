<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\PpidPublicApiController;
use App\Http\Controllers\BeritaPublicApiController;
use App\Http\Controllers\SliderPublicApiController;
use App\Http\Controllers\SakipRbPublicApiController;
=======
use App\Http\Controllers\SliderController;
use App\Http\Controllers\PpidInformasiBerkalaController;
use App\Http\Controllers\PpidInformasiDikecualikanController;
use App\Http\Controllers\PpidInformasiSertaMertaController;
use App\Http\Controllers\PpidInformasiSetiapSaatController;
use App\Http\Controllers\PpidLaporanAksesInformasiController;
>>>>>>> 508bd1e72a6243ca55d55a01adf524ca5b3f3712

/*
|--------------------------------------------------------------------------
| API Routes — Authenticated
|--------------------------------------------------------------------------
*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

<<<<<<< HEAD
/*
|--------------------------------------------------------------------------
| API Routes — PPID Publik (tanpa autentikasi)
|
| Digunakan oleh frontend publik (S1MB4-Frontend / React) untuk
| menampilkan data PPID kepada masyarakat.
| Semua endpoint hanya mengembalikan item berstatus 'publish'.
|--------------------------------------------------------------------------
*/
Route::prefix('ppid')->group(function () {
    Route::get('/berkala',                [PpidPublicApiController::class, 'berkala']);
    Route::get('/serta-merta',            [PpidPublicApiController::class, 'sertaMerta']);
    Route::get('/setiap-saat',            [PpidPublicApiController::class, 'setiapSaat']);
    Route::get('/dikecualikan',           [PpidPublicApiController::class, 'dikecualikan']);
    Route::get('/laporan-akses-informasi',[PpidPublicApiController::class, 'laporanAksesInformasi']);
});

/*
|--------------------------------------------------------------------------
| API Routes — Berita Publik (tanpa autentikasi)
|--------------------------------------------------------------------------
*/
Route::prefix('berita')->group(function () {
    Route::get('/',    [BeritaPublicApiController::class, 'index']);
    Route::get('/{id}',[BeritaPublicApiController::class, 'show'])->where('id', '[0-9]+');
});

/*
|--------------------------------------------------------------------------
| API Routes — Slider Publik (tanpa autentikasi)
|--------------------------------------------------------------------------
*/
Route::get('/sliders', [SliderPublicApiController::class, 'index']);

/*
|--------------------------------------------------------------------------
| API Routes — SAKIP-RB Publik (tanpa autentikasi)
|--------------------------------------------------------------------------
*/
Route::get('/sakip-rb', [SakipRbPublicApiController::class, 'index']);
=======
// Public API routes
Route::get('/sliders', [SliderController::class, 'apiIndex']);

Route::prefix('ppid')->group(function () {
    Route::get('/berkala', [PpidInformasiBerkalaController::class, 'apiIndex']);
    Route::get('/dikecualikan', [PpidInformasiDikecualikanController::class, 'apiIndex']);
    Route::get('/serta-merta', [PpidInformasiSertaMertaController::class, 'apiIndex']);
    Route::get('/setiap-saat', [PpidInformasiSetiapSaatController::class, 'apiIndex']);
    Route::get('/laporan-akses-informasi', [PpidLaporanAksesInformasiController::class, 'apiIndex']);
});
>>>>>>> 508bd1e72a6243ca55d55a01adf524ca5b3f3712
