<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\PpidInformasiBerkalaController;
use App\Http\Controllers\PpidInformasiDikecualikanController;
use App\Http\Controllers\PpidInformasiSertaMertaController;
use App\Http\Controllers\PpidInformasiSetiapSaatController;
use App\Http\Controllers\PpidLaporanAksesInformasiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API routes
Route::get('/sliders', [SliderController::class, 'apiIndex']);

Route::prefix('ppid')->group(function () {
    Route::get('/berkala', [PpidInformasiBerkalaController::class, 'apiIndex']);
    Route::get('/dikecualikan', [PpidInformasiDikecualikanController::class, 'apiIndex']);
    Route::get('/serta-merta', [PpidInformasiSertaMertaController::class, 'apiIndex']);
    Route::get('/setiap-saat', [PpidInformasiSetiapSaatController::class, 'apiIndex']);
    Route::get('/laporan-akses-informasi', [PpidLaporanAksesInformasiController::class, 'apiIndex']);
});
