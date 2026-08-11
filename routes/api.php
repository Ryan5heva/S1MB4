<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PpidPublicApiController;
use App\Http\Controllers\BeritaPublicApiController;
use App\Http\Controllers\SliderPublicApiController;
use App\Http\Controllers\SakipRbPublicApiController;

/*
|--------------------------------------------------------------------------
| API Routes — Authenticated
|--------------------------------------------------------------------------
*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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