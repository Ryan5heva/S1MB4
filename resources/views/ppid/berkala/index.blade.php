@extends('layouts.app')

@section('title', 'PPID — Informasi Berkala')
@section('page-title', 'PPID')
@section('page-subtitle', 'Pengelolaan dokumen & tautan informasi publik — ' . ($jenisDokumenAktif?->jenis_dokumen ?? 'Pilih kategori'))

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

    /* ── Dropdown kategori ── */
    .kategori-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 14px;
        padding-right: 2.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        color: #1e293b;
        border: 1.5px solid #e2e8f0;
        border-radius: 0.5rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-left: 0.75rem;
        background-color: #fff;
        cursor: pointer;
        transition: border-color 0.15s, box-shadow 0.15s;
        min-width: 260px;
    }
    .kategori-select:focus {
        outline: none;
        border-color: #148F9A;
        box-shadow: 0 0 0 3px rgba(20,143,154,0.12);
    }
</style>
@endpush

@section('content')

{{-- Header: Dropdown kategori + Tombol Tambah --}}
<div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <div class="flex items-center gap-3 flex-wrap">
        <form method="GET" action="{{ route('ppid.berkala.index') }}" id="formKategori">
            <select name="jenis_dokumen_id"
                    id="dropdownKategori"
                    class="kategori-select"
                    onchange="document.getElementById('formKategori').submit()">
                @php
                    // Urutan grup sesuai struktur klasifikasi resmi PPID
                    $urutanGrup = [
                        'Informasi Berkala',
                        'Informasi Serta Merta',
                        'Informasi Setiap Saat',
                        'Informasi Dikecualikan',
                        'Laporan Akses Informasi',
                        'Lainnya',
                    ];
                    $grupTersedia = collect($urutanGrup)->filter(fn($g) => $grupKategori->has($g));
                @endphp
                @foreach($grupTersedia as $grupNama)
                    <optgroup label="{{ $grupNama }}">
                        @foreach($grupKategori[$grupNama] as $jd)
                            <option value="{{ $jd->id }}"
                                {{ $jenisDokumenAktif?->id == $jd->id ? 'selected' : '' }}>
                                {{ $jd->jenis_dokumen }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </form>

        @if($jenisDokumenAktif)
            <span class="text-xs text-gray-400">
                {{ $items->count() }} item
            </span>
        @endif
    </div>

    {{-- Tombol Tambah Data — hanya tampil jika kategori tidak terdiri dari fixed items saja --}}
    @php
        $semuaFixed = $items->isNotEmpty() && $items->every(fn($i) => $i->is_fixed);
        $showTambah = $jenisDokumenAktif && !$semuaFixed;
    @endphp
    @if($showTambah)
        <a href="{{ route('ppid.berkala.create', ['jenis_dokumen_id' => $jenisDokumenAktif->id]) }}"
           class="btn-primary"
           id="addPpidBtn"
           style="padding:0.4rem 1rem; font-size:0.8125rem;">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </a>
    @endif
</div>

{{-- Tabel Utama --}}
<div class="bg-white rounded-2xl overflow-hidden"
     style="box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);">

    {{-- Card Header --}}
    <div class="flex items-center justify-between px-5 py-4"
         style="background: linear-gradient(135deg, #f0fdfa, #f8fafc); border-bottom: 1px solid #e2e8f0;">
        <div class="flex items-center gap-2">
            <i class="bi bi-folder2-open" style="color:#148F9A;"></i>
            <span class="text-sm font-semibold text-gray-800">
                {{ $jenisDokumenAktif?->jenis_dokumen ?? 'Pilih Kategori' }}
            </span>
            @if($jenisDokumenAktif)
                <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                    {{ $items->filter(fn($i) => $i->hasDokumen())->count() }}/{{ $items->count() }} terisi
                </span>
            @endif
        </div>
        @if($jenisDokumenAktif?->klasifikasi)
            <span class="text-xs text-gray-400 italic">
                Klasifikasi: {{ $jenisDokumenAktif->klasifikasi }}
            </span>
        @endif
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full ppid-table" id="tablePpidBerkala">
            <thead>
                <tr>
                    <th class="text-left w-8">No</th>
                    <th class="text-left">Nama Informasi</th>
                    <th class="text-center" style="width:80px;">Tahun</th>
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

                    {{-- Tahun --}}
                    <td class="text-center">
                        @if($item->tahun)
                            <span class="text-xs font-medium text-gray-600">{{ $item->tahun }}</span>
                        @else
                            <span class="text-xs text-gray-300">—</span>
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

                            {{-- Hapus — hanya untuk item non-fixed --}}
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
                    <td colspan="8" class="text-center py-10 text-gray-400">
                        <i class="bi bi-inbox" style="font-size:2rem;color:#d1d5db;display:block;margin-bottom:.5rem;"></i>
                        <p class="text-sm font-medium text-gray-500">Belum ada data untuk kategori ini</p>
                        @if($showTambah ?? false)
                            <p class="text-xs mt-1">Klik <strong>Tambah Data</strong> untuk menambahkan informasi.</p>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
{{-- jQuery + DataTables --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // DataTables untuk tabel utama PPID (jika ada data)
        if ($('#tablePpidBerkala tbody tr td').length > 1) {
            $('#tablePpidBerkala').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' },
                paging: false,
                info: false,
                lengthChange: false,
                searching: false,
                columnDefs: [{ orderable: false, targets: [7] }],
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
