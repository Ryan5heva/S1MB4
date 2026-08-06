@extends('layouts.app')

@section('title', 'Edit Jenis Dokumen')
@section('page-title', 'Edit Jenis Dokumen')
@section('page-subtitle', 'Perbarui data kategori dokumen PPID')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 p-6 max-w-2xl" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

    <form action="{{ route('jenis-dokumen.update', $jenisDokumen) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="form-label">Nama Jenis Dokumen</label>
            <input type="text" name="jenis_dokumen" value="{{ old('jenis_dokumen', $jenisDokumen->jenis_dokumen) }}"
                   class="form-input" required>
            @error('jenis_dokumen')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="form-label">Klasifikasi</label>
            <select name="klasifikasi" class="form-input" required>
                <option value="Berkala" @selected(old('klasifikasi', $jenisDokumen->klasifikasi) == 'Berkala')>Berkala</option>
                <option value="Dokumen" @selected(old('klasifikasi', $jenisDokumen->klasifikasi) == 'Dokumen')>Dokumen</option>
                <option value="sakip" @selected(old('klasifikasi', $jenisDokumen->klasifikasi) == 'sakip')>SAKIP</option>
            </select>
            @error('klasifikasi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-input" required>
                <option value="1" @selected(old('status', $jenisDokumen->status) == '1')>Aktif</option>
                <option value="0" @selected(old('status', $jenisDokumen->status) == '0')>Nonaktif</option>
            </select>
            @error('status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn-primary">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
            <a href="{{ route('jenis-dokumen.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection