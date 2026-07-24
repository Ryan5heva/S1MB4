@extends('layouts.app')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna Baru')
@section('page-subtitle', 'Isi form berikut untuk membuat akun pengguna baru')

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
        <h3 class="text-base font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-100">Informasi Pengguna</h3>

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-600">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       placeholder="Masukkan nama lengkap..."
                       class="form-input" required>
            </div>

            <div>
                <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       placeholder="contoh@email.com"
                       class="form-input" required>
            </div>

            <div>
                <label for="role" class="form-label">Role <span class="text-red-500">*</span></label>
                <select id="role" name="role" class="form-input" required>
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Role --</option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin"       {{ old('role') === 'admin'       ? 'selected' : '' }}>Admin</option>
                    <option value="operator"    {{ old('role') === 'operator'    ? 'selected' : '' }}>Operator</option>
                </select>
                <p class="text-xs text-gray-400 mt-1.5">Super Admin: akses penuh · Admin: CRUD · Operator: tambah & edit saja</p>
            </div>

            <div>
                <label for="password" class="form-label">Password <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password"
                       placeholder="Minimal 8 karakter..."
                       class="form-input" required>
                <p class="text-xs text-gray-400 mt-1.5">Minimal 8 karakter.</p>
            </div>

            <div>
                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       placeholder="Ulangi password..."
                       class="form-input" required>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit" id="submitUserBtn" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengguna
                </button>
                <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
