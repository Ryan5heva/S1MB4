<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ActivityLogController;

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

    // Video CRUD
    Route::resource('video', VideoController::class);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/riwayat', [ActivityLogController::class, 'index'])->name('riwayat.index');

    /*
    |--------------------------------------------------------------------------
    | Super Admin Only Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:super_admin')->group(function () {
        // User CRUD — hanya super_admin
        Route::resource('users', UserController::class);
    });
});