@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')
@section('page-subtitle', 'Perbarui informasi akun pengguna')

@section('content')

<div class="max-w-2xl">
    <div class="mb-4">
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Pengguna
        </a>
    </div>

    <div class="bg-white rounded-2xl p-8" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">
        <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-100">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold text-white" style="background: linear-gradient(135deg, #148F9A, #0d7a84);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800">{{ $user->name }}</h3>
                <p class="text-sm text-gray-400">{{ $user->email }}</p>
                @php
                    $roleStyle = match($user->role) {
                        'super_admin' => 'background:#fef3c7; color:#92400e;',
                        'admin'       => 'background:#ede9fe; color:#5b21b6;',
                        default       => 'background:#f0fdf4; color:#166534;',
                    };
                @endphp
                <span class="inline-block mt-1 text-xs font-semibold px-2.5 py-0.5 rounded-full" style="{{ $roleStyle }}">{{ $user->roleName() }}</span>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-600">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                       placeholder="Masukkan nama lengkap..."
                       class="form-input" required>
            </div>

            <div>
                <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                       placeholder="contoh@email.com"
                       class="form-input" required>
            </div>

            <div>
                <label for="role" class="form-label">Role <span class="text-red-500">*</span></label>
                <select id="role" name="role" class="form-input" required>
                    <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin"       {{ old('role', $user->role) === 'admin'       ? 'selected' : '' }}>Admin</option>
                    <option value="operator"    {{ old('role', $user->role) === 'operator'    ? 'selected' : '' }}>Operator</option>
                </select>
                <p class="text-xs text-gray-400 mt-1.5">Super Admin: akses penuh · Admin: CRUD · Operator: tambah & edit saja</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 border border-dashed border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-4">Ubah Password <span class="text-xs text-gray-400 font-normal">(opsional — kosongkan jika tidak ingin mengubah)</span></p>

                <div class="space-y-4">
                    <div>
                        <label for="password" class="form-label text-xs">Password Baru</label>
                        <input type="password" id="password" name="password"
                               placeholder="Minimal 8 karakter..."
                               class="form-input">
                    </div>
                    <div>
                        <label for="password_confirmation" class="form-label text-xs">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="Ulangi password baru..."
                               class="form-input">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit" id="updateUserBtn" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Perbarui Pengguna
                </button>
                <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
