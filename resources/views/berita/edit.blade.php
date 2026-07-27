@extends('layouts.app')

@section('title', 'Edit Berita')
@section('page-title', 'Edit Berita')
@section('page-subtitle', 'Perbarui informasi berita yang dipilih')

@section('content')

<div class="max-w-4xl">
    <div class="mb-4">
        <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Berita
        </a>
    </div>

    <div class="bg-white rounded-2xl p-8" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">
        <h3 class="text-base font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-100">Edit Berita</h3>

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-600">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('berita.update', $berita) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div>
                <label for="judul" class="form-label">Judul Berita <span class="text-red-500">*</span></label>
                <input type="text" id="judul" name="judul" value="{{ old('judul', $berita->judul) }}"
                       placeholder="Masukkan judul berita..."
                       class="form-input" required>
            </div>

            {{-- Konten --}}
            <div>
                <label for="konten" class="form-label">Konten Berita <span class="text-red-500">*</span></label>
                <textarea id="konten" name="konten" rows="8"
                          placeholder="Tulis konten berita di sini..."
                          class="form-input resize-y" required>{{ old('konten', $berita->konten) }}</textarea>
            </div>

            {{-- Gambar --}}
            <div>
                <label class="form-label">Gambar</label>

                {{-- Current Image --}}
                @if($berita->gambar)
                <div class="mb-3">
                    <p class="text-xs text-gray-500 mb-2">Gambar saat ini:</p>
                    <img src="{{ asset('storage/' . $berita->gambar) }}"
                         alt="{{ $berita->judul }}"
                         class="h-40 rounded-xl object-cover border border-gray-200">
                </div>
                @endif

                <div id="dropZone" class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center cursor-pointer hover:border-teal-400 hover:bg-teal-50/30 transition-all duration-200">
                    <svg class="w-7 h-7 mx-auto mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500">{{ $berita->gambar ? 'Klik untuk mengganti gambar' : 'Klik untuk memilih gambar' }}</p>
                    <p class="text-xs text-gray-400 mt-1">JPEG, PNG, GIF, WEBP — Maks. 2MB</p>
                    <input type="file" id="gambar" name="gambar" accept="image/*" class="hidden">
                </div>
                <div id="imagePreviewContainer" class="mt-3 hidden">
                    <p class="text-xs text-gray-500 mb-2">Gambar baru:</p>
                    <img id="imagePreview" src="" alt="Preview" class="h-40 rounded-xl object-cover border border-gray-200">
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit" id="updateBeritaBtn" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Perbarui Berita
                </button>
                <a href="{{ route('berita.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Image preview
    const dropZone = document.getElementById('dropZone');
    const gambarInput = document.getElementById('gambar');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');

    dropZone.addEventListener('click', () => gambarInput.click());

    gambarInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush