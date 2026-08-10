@extends('layouts.app')

@section('title', 'Tambah Jenis Dokumen')
@section('page-title', 'Tambah Jenis Dokumen')
@section('page-subtitle', 'Tambahkan kategori dokumen baru untuk PPID')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 p-6" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

    <form action="{{ route('jenis-dokumen.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="form-label">Nama Jenis Dokumen</label>
            <input type="text" name="jenis_dokumen" value="{{ old('jenis_dokumen') }}"
                   class="form-input" placeholder="Contoh: Profil Badan Publik" required>
            @error('jenis_dokumen')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="form-label">Klasifikasi</label>
            <input
                type="text"
                name="klasifikasi"
                list="klasifikasi-options"
                value="{{ old('klasifikasi') }}"
                class="form-input"
                placeholder="Pilih atau ketik klasifikasi..."
                required
                autocomplete="off"
            >
            <datalist id="klasifikasi-options">
                <option value="Berkala">
                <option value="Dokumen">
                <option value="SAKIP">
            </datalist>
            @error('klasifikasi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-input" required>
                <option value="1" @selected(old('status', '1') == '1')>Aktif</option>
                <option value="0" @selected(old('status') == '0')>Nonaktif</option>
            </select>
            @error('status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn-primary">
                <i class="bi bi-check-lg"></i> Simpan
            </button>
            <a href="{{ route('jenis-dokumen.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection