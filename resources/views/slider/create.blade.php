@extends('layouts.app')

@section('title', 'Tambah Gambar Slider')
@section('page-title', 'Tambah Gambar Slider')
@section('page-subtitle', 'Unggah gambar baru ke galeri')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 p-6" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

    <form action="{{ route('slider.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- URL Tujuan --}}
        <div>
            <label class="form-label">URL Tujuan <span class="text-gray-400 font-normal">(opsional)</span></label>
            <input type="url" name="url_tujuan" value="{{ old('url_tujuan') }}" class="form-input" placeholder="https://contoh.com (opsional, klik gambar akan menuju URL ini)">
            @error('url_tujuan')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Gambar --}}
        <div>
            <label class="form-label">Gambar <span class="text-red-500">*</span></label>
            <input type="file" name="gambar" accept="image/*" class="form-input" required>
            <p class="text-xs text-gray-400 mt-1">Format JPG/PNG/WEBP, maksimal 1MB. Disarankan rasio lebar (misal 1920x700px).</p>
            @error('gambar')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Urutan --}}
        <div>
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" value="{{ old('urutan', 0) }}" min="0" class="form-input">
            <p class="text-xs text-gray-400 mt-1">Angka lebih kecil tampil lebih dulu (jika sudah dicentang).</p>
            @error('urutan')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="px-4 py-3 rounded-lg bg-amber-50 border border-amber-100">
            <p class="text-xs text-amber-700">
                <i class="bi bi-info-circle"></i>
                Gambar akan masuk ke galeri dalam keadaan <strong>tersembunyi</strong>. Centang gambar dari halaman Slider untuk menampilkannya di web.
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn-primary">
                <i class="bi bi-check-lg"></i>
                Unggah
            </button>
            <a href="{{ route('slider.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection