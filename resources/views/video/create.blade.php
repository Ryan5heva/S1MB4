@extends('layouts.app')

@section('title', 'Tambah Video')
@section('page-title', 'Tambah Video')
@section('page-subtitle', 'Tambahkan tautan video baru')

@section('content')
<div>
    <div class="mb-4">
        <a href="{{ route('video.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Video
        </a>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
        <h3 class="text-base font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-100">Informasi Video Baru</h3>

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-600">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('video.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="judul_video" class="form-label">Judul Video <span class="text-red-500">*</span></label>
                <input type="text" id="judul_video" name="judul_video" value="{{ old('judul_video') }}"
                       placeholder="Masukkan judul video..."
                       class="form-input" required>
            </div>

            <div>
                <label for="url_video" class="form-label">Tautan (URL) YouTube <span class="text-red-500">*</span></label>
                <input type="url" id="url_video" name="url_video" value="{{ old('url_video') }}"
                       placeholder="Contoh: https://www.youtube.com/watch?v=xxxxx"
                       class="form-input" required>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">
                    Simpan Video
                </button>
                <a href="{{ route('video.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection