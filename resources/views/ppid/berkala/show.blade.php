@extends('layouts.app')

@section('title', 'Detail — ' . $ppid->nama_informasi)
@section('page-title', 'Detail Informasi Berkala')
@section('page-subtitle', 'Informasi lengkap data PPID — Informasi Berkala')

@push('styles')
<style>
    /* Badge styles */
    .badge-publish  { background: #dcfce7; color: #15803d; }
    .badge-draft    { background: #f1f5f9; color: #64748b; }
    .badge-dokumen  { background: #dbeafe; color: #1d4ed8; }
    .badge-link     { background: #ede9fe; color: #6d28d9; }
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.2rem 0.65rem;
        border-radius: 9999px;
    }

    /* Detail row */
    .detail-row {
        display: flex;
        gap: 1rem;
        padding: 0.875rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label {
        width: 11rem;
        flex-shrink: 0;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #6b7280;
    }
    .detail-value {
        font-size: 0.875rem;
        color: #1f2937;
        flex: 1;
        min-width: 0;
        word-break: break-word;
    }

    /* PDF embed */
    .pdf-embed {
        width: 100%;
        height: 600px;
        border: none;
        border-radius: 0.75rem;
    }
</style>
@endpush

@section('content')

<div class="max-w-4xl">

    {{-- Back + Actions --}}
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('ppid.berkala.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar Informasi Berkala
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('ppid.berkala.edit', $ppid) }}" class="btn-edit" id="editDetailBtn">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @if(Auth::user()->canDelete())
            <button type="button"
                    class="btn-danger"
                    id="deleteDetailBtn"
                    onclick="konfirmasiHapus()">
                <i class="bi bi-trash3"></i> Hapus
            </button>
            <form id="formDeleteDetail"
                  method="POST"
                  action="{{ route('ppid.berkala.destroy', $ppid) }}"
                  class="hidden">
                @csrf
                @method('DELETE')
            </form>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">

        {{-- Card Header --}}
        <div class="px-8 py-5 border-b border-gray-100" style="background: linear-gradient(135deg, #f0fdfa, #fff);">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: rgba(20,143,154,0.1);">
                    @if($ppid->jenis === 'dokumen')
                        <i class="bi bi-file-earmark-text" style="color:#148F9A; font-size:1.25rem;"></i>
                    @else
                        <i class="bi bi-link-45deg" style="color:#148F9A; font-size:1.25rem;"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-base font-semibold text-gray-800">{{ $ppid->nama_informasi }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $ppid->kategori }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        {{-- Status badge --}}
                        @if($ppid->status === 'publish')
                            <span class="badge badge-publish"><i class="bi bi-circle-fill" style="font-size:0.4rem;"></i> Publish</span>
                        @else
                            <span class="badge badge-draft"><i class="bi bi-circle" style="font-size:0.4rem;"></i> Draft</span>
                        @endif
                        {{-- Jenis badge --}}
                        @if($ppid->jenis === 'dokumen')
                            <span class="badge badge-dokumen"><i class="bi bi-file-earmark-text"></i> Dokumen</span>
                        @else
                            <span class="badge badge-link"><i class="bi bi-link-45deg"></i> Link</span>
                        @endif
                        {{-- Urutan --}}
                        <span class="text-xs text-gray-400">Urutan: #{{ $ppid->urutan }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Rows --}}
        <div class="px-8 py-4">

            <div class="detail-row">
                <span class="detail-label">ID</span>
                <span class="detail-value text-gray-400">#{{ $ppid->id }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Jenis Menu PPID</span>
                <span class="detail-value">{{ $ppid->jenis_menu_label }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Kategori</span>
                <span class="detail-value font-medium">{{ $ppid->kategori }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Nama Informasi</span>
                <span class="detail-value font-semibold text-gray-900">{{ $ppid->nama_informasi }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Deskripsi</span>
                <span class="detail-value text-gray-600">
                    {{ $ppid->deskripsi ?? '—' }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Tanggal Publish</span>
                <span class="detail-value">
                    {{ $ppid->published_at ? $ppid->published_at->format('d M Y') : '—' }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Dibuat oleh</span>
                <span class="detail-value">{{ $ppid->user->name ?? '—' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Dibuat pada</span>
                <span class="detail-value">{{ $ppid->created_at->format('d M Y H:i') }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Terakhir diubah</span>
                <span class="detail-value">{{ $ppid->updated_at->format('d M Y H:i') }}</span>
            </div>

        </div>
    </div>

    {{-- Dokumen / Link Preview Card --}}
    @if($ppid->jenis === 'dokumen' && $ppid->file)
    <div class="mt-6 bg-white rounded-2xl overflow-hidden" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">
        <div class="px-8 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">
                <i class="bi bi-file-earmark-pdf text-red-500 me-2"></i>
                Dokumen Terlampir
            </h3>
            <a href="{{ asset('storage/' . $ppid->file) }}"
               target="_blank"
               download
               class="btn-primary"
               style="padding: 0.375rem 0.875rem; font-size: 0.8125rem;">
                <i class="bi bi-download"></i> Unduh
            </a>
        </div>
        <div class="p-6">
            @if($ppid->isPdf())
                {{-- Preview PDF langsung di halaman --}}
                <embed src="{{ asset('storage/' . $ppid->file) }}"
                       type="application/pdf"
                       class="pdf-embed"
                       title="{{ $ppid->nama_informasi }}">
                <p class="text-xs text-gray-400 mt-2 text-center">
                    Jika PDF tidak tampil, gunakan tombol "Unduh" di atas.
                </p>
            @else
                {{-- Non-PDF: tampilkan link download --}}
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <i class="bi bi-file-earmark-text" style="font-size:2rem; color:#9ca3af;"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $ppid->file_name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Preview tidak tersedia untuk format ini.</p>
                        <a href="{{ asset('storage/' . $ppid->file) }}"
                           target="_blank"
                           class="inline-flex items-center gap-1.5 text-xs text-teal-600 hover:underline mt-1">
                            <i class="bi bi-box-arrow-up-right"></i> Buka di tab baru
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @elseif($ppid->jenis === 'link' && $ppid->url)
    <div class="mt-6 bg-white rounded-2xl overflow-hidden" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">
        <div class="px-8 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">
                <i class="bi bi-link-45deg text-indigo-500 me-2"></i>
                Tautan / Link
            </h3>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-4 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                <i class="bi bi-globe" style="font-size:1.5rem; color:#6d28d9;"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-indigo-400 mb-0.5">URL</p>
                    <a href="{{ $ppid->url }}"
                       target="_blank"
                       class="text-sm font-medium text-indigo-700 hover:text-indigo-900 hover:underline break-all">
                        {{ $ppid->url }}
                    </a>
                </div>
                <a href="{{ $ppid->url }}"
                   target="_blank"
                   class="btn-primary flex-shrink-0"
                   style="padding: 0.375rem 0.875rem; font-size: 0.8125rem;">
                    <i class="bi bi-box-arrow-up-right"></i> Buka
                </a>
            </div>
        </div>
    </div>
    @endif

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function konfirmasiHapus() {
        Swal.fire({
            title: 'Hapus Data?',
            html: 'Data <strong>"{{ addslashes($ppid->nama_informasi) }}"</strong> akan dihapus secara permanen beserta file-nya jika ada.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bi bi-trash3"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formDeleteDetail').submit();
            }
        });
    }
</script>
@endpush
