@extends('layouts.app')

@section('title', 'Setting')
@section('page-title', 'Setting')
@section('page-subtitle', 'Kelola akun dan preferensi Anda')

@section('content')

{{-- ============================================================
     SECTIONS 1 & 2 — UBAH EMAIL + UBAH PASSWORD (semua role)
     Grid: 1 kolom di mobile, 2 kolom berdampingan di desktop (lg+)
============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

{{-- ─── Kolom Kiri: Ubah Email ─── --}}
<div>

    <div class="flex items-center gap-3 mb-4">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background: rgba(20,143,154,0.1);">
            <i class="bi bi-envelope" style="color:#148F9A; font-size:1.1rem;"></i>
        </div>
        <div>
            <h3 class="text-base font-semibold text-gray-800">Ubah Email</h3>
            <p class="text-xs text-gray-400 mt-0.5">Perbarui alamat email akun Anda</p>
        </div>
    </div>

    @if(session('success_email'))
        <div class="alert-success rounded-lg px-4 py-3 flex items-center gap-3 mb-4" id="flashEmailAlert">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('success_email') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-6" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">
        <form method="POST" action="{{ route('settings.updateEmail') }}" class="space-y-4">
            @csrf

            <div>
                <label class="form-label">Email Saat Ini</label>
                <p class="text-sm text-gray-600 py-2 px-3 bg-gray-50 rounded-lg border border-gray-100">{{ Auth::user()->email }}</p>
            </div>

            <div>
                <label for="email" class="form-label">Email Baru <span class="text-red-500">*</span></label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-input" placeholder="email@baru.com" required>
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="current_password_email" class="form-label">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input id="current_password_email" type="password" name="current_password"
                       class="form-input" placeholder="Masukkan password Anda saat ini" required>
                @error('current_password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-1">
                <button type="submit" class="btn-primary" id="updateEmailBtn">
                    <i class="bi bi-check-lg"></i>
                    Simpan Email
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ─── Kolom Kanan: Ubah Password ─── --}}
<div>

    <div class="flex items-center gap-3 mb-4">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background: rgba(20,143,154,0.1);">
            <i class="bi bi-lock" style="color:#148F9A; font-size:1.1rem;"></i>
        </div>
        <div>
            <h3 class="text-base font-semibold text-gray-800">Ubah Password</h3>
            <p class="text-xs text-gray-400 mt-0.5">Pastikan menggunakan password yang kuat dan unik</p>
        </div>
    </div>

    @if(session('success_password'))
        <div class="alert-success rounded-lg px-4 py-3 flex items-center gap-3 mb-4" id="flashPasswordAlert">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium">{{ session('success_password') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-6" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">
        <form method="POST" action="{{ route('settings.updatePassword') }}" class="space-y-4">
            @csrf

            <div>
                <label for="current_password_pw" class="form-label">Password Saat Ini <span class="text-red-500">*</span></label>
                <input id="current_password_pw" type="password" name="current_password"
                       class="form-input" placeholder="Password Anda saat ini" required>
                @error('current_password', 'updatePassword')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="form-label">Password Baru <span class="text-red-500">*</span></label>
                <input id="password" type="password" name="password"
                       class="form-input" placeholder="Minimal 8 karakter" required>
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="form-input" placeholder="Ulangi password baru" required>
            </div>

            <div class="pt-1">
                <button type="submit" class="btn-primary" id="updatePasswordBtn">
                    <i class="bi bi-check-lg"></i>
                    Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>

</div>{{-- end grid (Email + Password) --}}

{{-- ============================================================
     SECTION 3 — RIWAYAT AKTIVITAS (Admin & Super Admin)
============================================================ --}}
@if($canViewActivityLog)
<div class="mb-8">

    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: rgba(20,143,154,0.1);">
                <i class="bi bi-clock-history" style="color:#148F9A; font-size:1.1rem;"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800">Riwayat Aktivitas</h3>
                <p class="text-xs text-gray-400 mt-0.5">Catatan aktivitas pengguna di sistem</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">Tanggal &amp; Waktu</th>
                        <th class="text-left">Pengguna</th>
                        <th class="text-left">Aktivitas</th>
                        <th class="text-left">Keterangan</th>
                        @if(auth()->user()->isSuperAdmin())
                        <th class="text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr>
                        <td>{{ $logs->firstItem() + $index }}</td>
                        <td class="text-sm text-gray-500">{{ $log->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $log->user->name ?? 'Sistem' }} <span class="text-xs text-gray-400">({{ $log->user->roleName() ?? '-' }})</span></td>
                        <td>{{ $log->aktivitas }}</td>
                        <td class="text-sm text-gray-600">{{ $log->keterangan }}</td>
                        @if(auth()->user()->isSuperAdmin())
                        <td class="text-center">
                            <form method="POST" action="{{ route('riwayat.destroy', $log) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus riwayat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Hapus</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isSuperAdmin() ? 6 : 5 }}" class="text-center py-10 text-gray-400">
                            Belum ada riwayat aktivitas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endif

{{-- ============================================================
     SECTION 4 — KELOLA PENGGUNA (Super Admin saja)
============================================================ --}}
@if(Auth::user()->isSuperAdmin())
<div>

    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: rgba(20,143,154,0.1);">
                <i class="bi bi-people" style="color:#148F9A; font-size:1.1rem;"></i>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800">Kelola Pengguna</h3>
                <p class="text-xs text-gray-400 mt-0.5">Manajemen akun pengguna sistem &mdash; Total {{ $users->total() }} pengguna terdaftar</p>
            </div>
        </div>
        <a href="{{ route('users.create') }}" class="btn-primary" id="addUserFromSettingsBtn">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Tambah Pengguna
        </a>
    </div>

    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="text-left w-8">#</th>
                        <th class="text-left">Pengguna</th>
                        <th class="text-left">Email</th>
                        <th class="text-left">Role</th>
                        <th class="text-left">Bergabung</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td class="text-gray-400 text-xs">{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold text-white flex-shrink-0"
                                     style="background: linear-gradient(135deg, #148F9A, #0d7a84);">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $user->name }}</p>
                                    @if($user->id === Auth::id())
                                        <span class="text-xs bg-teal-50 text-teal-600 px-2 py-0.5 rounded-full font-medium">Anda</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-sm text-gray-600">{{ $user->email }}</td>
                        <td>
                            @php
                                $roleStyle = match($user->role) {
                                    'super_admin' => 'background:#fef3c7; color:#92400e;',
                                    'admin'       => 'background:#ede9fe; color:#5b21b6;',
                                    default       => 'background:#f0fdf4; color:#166534;',
                                };
                            @endphp
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="{{ $roleStyle }}">
                                {{ $user->roleName() }}
                            </span>
                        </td>
                        <td class="text-sm text-gray-500 whitespace-nowrap">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="btn-edit" id="editUserSettings{{ $user->id }}">Edit</a>
                                @if($user->id !== Auth::id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus pengguna {{ addslashes($user->name) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" id="deleteUserSettings{{ $user->id }}">Hapus</button>
                                </form>
                                @else
                                <span class="text-xs text-gray-300 px-3 py-1.5 rounded-md border border-dashed border-gray-200">Tidak dapat dihapus</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500">Belum ada pengguna</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endif

@endsection
