@extends('layouts.app')

@section('title', 'Tambah Slider')
@section('page-title', 'Tambah Slider')
@section('page-subtitle', 'Tambahkan banner slider baru')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 p-6 max-w-2xl" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

    <form action="{{ route('slider.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Judul --}}
        <div>
            <label class="form-label">Judul <span class="text-red-500">*</span></label>
            <input type="text" name="judul" value="{{ old('judul') }}"
                   class="form-input" placeholder="Judul banner" required>
            @error('judul')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="form-input" placeholder="Deskripsi singkat (opsional)">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Gambar --}}
        <div>
            <label class="form-label">Gambar Banner <span class="text-red-500">*</span></label>
            <input type="file" name="gambar" accept="image/*" class="form-input" required>
            <p class="text-xs text-gray-400 mt-1">Format JPG/PNG/WEBP, maksimal 2MB. Disarankan rasio lebar (misal 1600x600px).</p>
            @error('gambar')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Url Tujuan --}}
        <div>
            <label class="form-label">Url Tujuan</label>
            <input type="url" name="url_tujuan" value="{{ old('url_tujuan') }}"
                   class="form-input" placeholder="https://example.com (opsional, jika banner bisa diklik)">
            @error('url_tujuan')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            {{-- Urutan --}}
            <div>
                <label class="form-label">Urutan</label>
                <input type="number" name="urutan" value="{{ old('urutan', 0) }}" min="0"
                       class="form-input">
                @error('urutan')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="form-label">Status <span class="text-red-500">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn-primary">
                <i class="bi bi-check-lg"></i>
                Simpan
            </button>
            <a href="{{ route('slider.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection