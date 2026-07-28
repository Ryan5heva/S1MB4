@extends('layouts.app')

@section('title', 'PPID — Informasi Berkala')
@section('page-title', 'Informasi Berkala')
@section('page-subtitle', 'Pengelolaan dokumen & tautan PPID — Informasi yang wajib disediakan secara berkala')

@push('styles')
<style>
    /* ── Badge ── */
    .badge {
        display: inline-flex; align-items: center; gap: 0.25rem;
        font-size: 0.7rem; font-weight: 600;
        padding: 0.175rem 0.55rem; border-radius: 9999px; white-space: nowrap;
    }
    .badge-publish  { background: #dcfce7; color: #15803d; }
    .badge-draft    { background: #f1f5f9; color: #64748b; }
    .badge-dokumen  { background: #dbeafe; color: #1d4ed8; }
    .badge-link     { background: #ede9fe; color: #6d28d9; }
    .badge-belum    { background: #fff7ed; color: #c2410c; }

    /* ── Section card ── */
    .ppid-section { margin-bottom: 1.5rem; }
    .ppid-section-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.875rem 1.25rem;
        background: linear-gradient(135deg, #f0fdfa, #f8fafc);
        border-bottom: 1px solid #e2e8f0;
        border-radius: 1rem 1rem 0 0;
    }
    .ppid-section-title {
        font-size: 0.875rem; font-weight: 600; color: #1e293b;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .ppid-section-badge {
        font-size: 0.7rem; font-weight: 500; color: #94a3b8;
        background: #f1f5f9; padding: 0.125rem 0.5rem; border-radius: 9999px;
    }

    /* ── Table cells ── */
    .ppid-table th {
        background: #f8fafc; font-size: 0.7rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;
        padding: 0.625rem 0.875rem;
    }
    .ppid-table td {
        padding: 0.625rem 0.875rem; font-size: 0.8125rem;
        color: #374151; border-bottom: 1px solid #f1f5f9; vertical-align: middle;
    }
    .ppid-table tr:last-child td { border-bottom: none; }
    .ppid-table tr:hover td { background: #fafafa; }

    /* ── Quick Nav ── */
    .quick-nav {
        display: flex; flex-wrap: wrap; gap: 0.5rem;
        padding: 0.875rem 1rem;
        background: white; border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
    }
    .quick-nav a {
        font-size: 0.75rem; font-weight: 500;
        color: #475569; padding: 0.25rem 0.625rem;
        background: #f1f5f9; border-radius: 0.375rem;
        text-decoration: none; transition: all 0.15s;
        white-space: nowrap;
    }
    .quick-nav a:hover { background: #148F9A; color: white; }

    /* ── Progress summary ── */
    .progress-bar-inner { height: 6px; border-radius: 9999px; background: #148F9A; transition: width 0.4s; }
    .progress-bar-bg    { height: 6px; border-radius: 9999px; background: #e2e8f0; overflow: hidden; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h3 class="text-base font-semibold text-gray-800">Daftar Informasi Berkala</h3>
        <p class="text-xs text-gray-400 mt-0.5">
            Sesuai ketentuan UU No. 14/2008 — Nama Informasi bersifat tetap, admin hanya mengelola dokumen/tautan.
        </p>
    </div>
</div>

{{-- Progress Overview --}}
@php
    $totalItems   = $sections->flatten()->count();
    $filledItems  = $sections->flatten()->filter(fn($i) => $i->hasDokumen())->count();
    $pct          = $totalItems > 0 ? round(($filledItems / $totalItems) * 100) : 0;
@endphp
<div class="bg-white rounded-xl p-4 mb-5 flex items-center gap-5" style="box-shadow:0 1px 3px rgba(0,0,0,0.06);">
    <div class="flex-1">
        <div class="flex items-center justify-between mb-1">
            <span class="text-xs font-medium text-gray-600">Kelengkapan Dokumen</span>
            <span class="text-xs font-semibold" style="color:#148F9A;">{{ $filledItems }} / {{ $totalItems }} item terisi</span>
        </div>
        <div class="progress-bar-bg">
            <div class="progress-bar-inner" style="width:{{ $pct }}%"></div>
        </div>
    </div>
    <div class="text-center flex-shrink-0">
        <p class="text-2xl font-bold" style="color:#148F9A;">{{ $pct }}%</p>
        <p class="text-xs text-gray-400">Terisi</p>
    </div>
</div>

{{-- Quick Navigation --}}
<div class="quick-nav">
    <span class="text-xs font-semibold text-gray-400 self-center mr-1">Lompat ke:</span>
    @foreach($sections as $kategori => $items)
        <a href="#section-{{ Str::slug($kategori) }}">
            {{ Str::limit($kategori, 28) }}
        </a>
    @endforeach
</div>

{{-- Sections --}}
@foreach($sections as $kategori => $items)
@php
    $slug         = Str::slug($kategori);
    $isKetenaga   = ($kategori === 'Ketenagakerjaan');
    $sectionFill  = $items->filter(fn($i) => $i->hasDokumen())->count();
    $sectionTotal = $items->count();
@endphp
<div class="ppid-section bg-white rounded-2xl overflow-hidden" id="section-{{ $slug }}"
     style="box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);">

    {{-- Section Header --}}
    <div class="ppid-section-header">
        <div class="ppid-section-title">
            <i class="bi bi-folder2-open" style="color:#148F9A;"></i>
            {{ $kategori }}
            @if($sectionTotal > 0)
                <span class="ppid-section-badge">
                    {{ $sectionFill }}/{{ $sectionTotal }} terisi
                </span>
            @endif
        </div>
        @if($isKetenaga)
            <a href="{{ route('ppid.berkala.create') }}" class="btn-primary" style="padding:0.35rem 0.875rem; font-size:0.8rem;" id="addKetenagaBtn">
                <i class="bi bi-plus-lg"></i> Tambah Data
            </a>
        @endif
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full ppid-table"
               @if($isKetenaga) id="tableKetenagakerjaan" @endif>
            <thead>
                <tr>
                    <th class="text-left w-8">No</th>
                    <th class="text-left">Nama Informasi</th>
                    <th class="text-center" style="width:90px;">Jenis</th>
                    <th class="text-left">Dokumen / Link</th>
                    <th class="text-center" style="width:90px;">Status</th>
                    <th class="text-left" style="width:120px;">Terakhir Diubah</th>
                    <th class="text-center" style="width:110px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $idx => $item)
                <tr>
                    {{-- No --}}
                    <td class="text-gray-400 text-xs">{{ $idx + 1 }}</td>

                    {{-- Nama Informasi --}}
                    <td>
                        <span class="font-medium text-gray-800" style="font-size:0.8125rem;">{{ $item->nama_informasi }}</span>
                        @if($item->deskripsi)
                            <p class="text-xs text-gray-400 mt-0.5 leading-snug" style="max-width:280px;">{{ Str::limit($item->deskripsi, 60) }}</p>
                        @endif
                    </td>

                    {{-- Jenis --}}
                    <td class="text-center">
                        @if($item->jenis === 'dokumen')
                            <span class="badge badge-dokumen"><i class="bi bi-file-earmark-text"></i> Dokumen</span>
                        @elseif($item->jenis === 'link')
                            <span class="badge badge-link"><i class="bi bi-link-45deg"></i> Link</span>
                        @else
                            <span class="badge badge-belum"><i class="bi bi-dash-circle"></i> Belum</span>
                        @endif
                    </td>

                    {{-- Dokumen / Link --}}
                    <td>
                        @if($item->jenis === 'dokumen' && $item->file)
                            <a href="{{ asset('storage/' . $item->file) }}" target="_blank"
                               class="inline-flex items-center gap-1 text-xs text-teal-600 hover:underline"
                               title="{{ $item->file_name }}">
                                <i class="bi bi-download"></i>
                                {{ Str::limit($item->file_name, 32) }}
                            </a>
                        @elseif($item->jenis === 'link' && $item->url)
                            <a href="{{ $item->url }}" target="_blank"
                               class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:underline"
                               style="max-width:220px; overflow:hidden; text-overflow:ellipsis; display:block; white-space:nowrap;"
                               title="{{ $item->url }}">
                                <i class="bi bi-box-arrow-up-right"></i>
                                {{ $item->url }}
                            </a>
                        @else
                            <span class="text-xs text-gray-300 italic">Belum ada dokumen/link</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="text-center">
                        @if($item->status === 'publish')
                            <span class="badge badge-publish"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Publish</span>
                        @else
                            <span class="badge badge-draft"><i class="bi bi-circle" style="font-size:.4rem;"></i> Draft</span>
                        @endif
                    </td>

                    {{-- Terakhir Diubah --}}
                    <td>
                        @if($item->updated_at && $item->hasDokumen())
                            <span class="text-xs text-gray-600 block">{{ $item->updated_at->format('d M Y') }}</span>
                            <span class="text-xs text-gray-400">{{ $item->updated_at->format('H:i') }}</span>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            {{-- Edit / Tambah Dokumen --}}
                            @if($item->hasDokumen())
                                <a href="{{ route('ppid.berkala.edit', $item) }}"
                                   class="btn-edit" style="padding:0.3rem 0.6rem; font-size:0.75rem;"
                                   id="editBerkala{{ $item->id }}" title="Edit Dokumen/Link">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            @else
                                <a href="{{ route('ppid.berkala.edit', $item) }}"
                                   class="btn-primary" style="padding:0.3rem 0.6rem; font-size:0.75rem;"
                                   id="tambahBerkala{{ $item->id }}" title="Tambah Dokumen/Link">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </a>
                            @endif

                            {{-- Hapus — hanya untuk item non-fixed (Ketenagakerjaan) --}}
                            @if(! $item->is_fixed && Auth::user()->canDelete())
                                <button type="button"
                                    class="btn-danger" style="padding:0.3rem 0.5rem; font-size:0.75rem;"
                                    id="delBerkala{{ $item->id }}"
                                    title="Hapus"
                                    onclick="konfirmasiHapus({{ $item->id }}, '{{ addslashes($item->nama_informasi) }}')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                                <form id="formDelete{{ $item->id }}"
                                      method="POST"
                                      action="{{ route('ppid.berkala.destroy', $item) }}"
                                      class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-gray-400">
                        @if($isKetenaga)
                            <i class="bi bi-plus-circle" style="font-size:2rem;color:#d1d5db;display:block;margin-bottom:.5rem;"></i>
                            <p class="text-sm font-medium text-gray-500">Belum ada data Ketenagakerjaan</p>
                            <p class="text-xs mt-1">Klik <strong>Tambah Data</strong> untuk menambahkan informasi.</p>
                        @else
                            <span class="text-xs">Tidak ada data</span>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endforeach

{{-- Back to top --}}
<div class="text-center mt-4">
    <a href="#" class="text-xs text-gray-400 hover:text-teal-600 inline-flex items-center gap-1 transition-colors">
        <i class="bi bi-arrow-up-circle"></i> Kembali ke atas
    </a>
</div>

@endsection

@push('scripts')
{{-- jQuery + DataTables untuk tabel Ketenagakerjaan yang dinamis --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // DataTables hanya untuk Ketenagakerjaan (tabel yang dinamis)
        if ($('#tableKetenagakerjaan').length && $('#tableKetenagakerjaan tbody tr td').length > 1) {
            $('#tableKetenagakerjaan').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
                pageLength: 10,
                columnDefs: [{ orderable: false, targets: [6] }],
            });
        }
    });

    function konfirmasiHapus(id, nama) {
        Swal.fire({
            title: 'Hapus Data?',
            html: `Data <strong>"${nama}"</strong> akan dihapus secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bi bi-trash3"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then(r => r.isConfirmed && document.getElementById('formDelete' + id).submit());
    }
</script>
@endpush
