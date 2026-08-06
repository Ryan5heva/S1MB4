<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Tampilkan halaman Setting yang berisi:
     *  - Riwayat Aktivitas  (untuk Admin & Super Admin)
     *  - Kelola Pengguna    (hanya untuk Super Admin)
     */
    public function index()
    {
        // Operator tidak boleh akses
        if (auth()->user()->isOperator()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Riwayat Aktivitas — selalu dimuat untuk Admin & Super Admin
        $logs = ActivityLog::with('user')->latest()->paginate(15, ['*'], 'logs_page');

        // Kelola Pengguna — hanya dimuat untuk Super Admin
        $users = auth()->user()->isSuperAdmin()
            ? User::latest()->paginate(10, ['*'], 'users_page')
            : null;

        return view('settings.index', compact('logs', 'users'));
    }
}