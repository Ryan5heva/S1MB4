<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Tampilkan halaman Setting.
     *
     * Semua role melihat halaman ini.
     * Konten yang ditampilkan disesuaikan berdasarkan role di view:
     *  - Email & Password  → semua role
     *  - Riwayat Aktivitas → Admin & Super Admin
     *  - Kelola Pengguna   → Super Admin
     */
    public function index()
    {
        $user = Auth::user();

        // Riwayat Aktivitas — hanya dimuat jika user berhak melihatnya
        $logs = $user->canViewActivityLog()
            ? ActivityLog::with('user')->latest()->paginate(15, ['*'], 'logs_page')
            : null;

        // Kelola Pengguna — hanya dimuat untuk Super Admin
        $users = $user->isSuperAdmin()
            ? User::latest()->paginate(10, ['*'], 'users_page')
            : null;

        return view('settings.index', [
            'logs'               => $logs,
            'users'              => $users,
            'canViewActivityLog' => $user->canViewActivityLog(),
        ]);
    }

    /**
     * Perbarui email milik user yang sedang login.
     * Tersedia untuk semua role.
     */
    public function updateEmail(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'email'            => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['required', 'current_password'],
        ], [
            'email.unique'              => 'Email ini sudah digunakan oleh pengguna lain.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
        ]);

        $user->update(['email' => $request->email]);

        ActivityLog::catat('Edit Data', 'Memperbarui alamat email akun sendiri.');

        return back()->with('success_email', 'Email berhasil diperbarui.');
    }

    /**
     * Perbarui password milik user yang sedang login.
     * Tersedia untuk semua role.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed'                => 'Konfirmasi password tidak cocok.',
        ]);

        Auth::user()->update(['password' => Hash::make($request->password)]);

        ActivityLog::catat('Edit Data', 'Memperbarui password akun sendiri.');

        return back()->with('success_password', 'Password berhasil diperbarui.');
    }
}