@extends('layouts.app')

@section('title', 'PPID — Informasi Setiap Saat')
@section('page-title', 'Informasi Setiap Saat')
@section('page-subtitle', 'Pengelolaan dokumen & tautan PPID — Informasi yang tersedia setiap saat untuk publik')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
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

    /* ── DataTables override ── */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db; border-radius: 0.5rem;
        padding: 0.375rem 0.75rem; font-size: 0.8125rem;
        outline: none; margin-left: 0.5rem; transition: border-color .2s, box-shadow .2s;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #148F9A; box-shadow: 0 0 0 3px rgba(20,143,154,.12);
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #d1d5db; border-radius: 0.5rem;
        padding: 0.25rem 0.5rem; font-size: 0.8125rem; margin: 0 0.25rem;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_length { font-size: 0.8125rem; color: #6b7280; }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.375rem !important; font-size: 0.8125rem !important;
        padding: 0.25rem 0.625rem !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #148F9A !important; border-color: #148F9A !important; color: #fff !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #f0fdf4 !important; border-color: #d1fae5 !important; color: #148F9A !important;
    }

    /* ── Table ── */
    .table-setiap-saat thead th {
        background: #f8fafc; font-size: 0.7rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;
        padding: 0.75rem 1rem; white-space: nowrap;
    }
    .table-setiap-saat tbody td {
        padding: 0.75rem 1rem; font-size: 0.8125rem;
        color: #374151; vertical-align: middle;
    }
    .table-setiap-saat tbody tr:hover td { background: #fafafa; }

    /* ── Section Cards ── */
    .section-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .section-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }
    .section-icon {
        width: 2rem; height: 2rem;
        border-radius: 0.5rem;
        display: inline-flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')

{{-- Flash Message --}}
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 flex items-center gap-2">
    <i class="bi bi-check-circle-fill text-green-500"></i>
    <span class="text-sm text-green-700">{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div class="mb-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-center gap-2">
    <i class="bi bi-exclamation-circle-fill text-red-500"></i>
    <span class="text-sm text-red-700">{{ session('error') }}</span>
</div>
@endif

{{-- ══════════════════════════════════════════════
     TABEL 1 — Daftar Informasi Publik (DIP)
══════════════════════════════════════════════ --}}
@php
    $sectionDIP  = $sections->get('Daftar Informasi Publik (DIP)',   collect());
    $sectionSKM  = $sections->get('Laporan Survei Kepuasan Masyarakat (SKM)', collect());
    $sectionSP   = $sections->get('Standar Pelayanan', collect());
@endphp

<div class="section-card">
    <div class="section-card-header">
        <div class="section-icon" style="background: rgba(59,130,246,0.1);">
            <i class="bi bi-file-earmark-text" style="color:#2563eb; font-size:0.95rem;"></i>
        </div>
        <div>
            <span class="text-sm font-semibold text-gray-700">Daftar Informasi Publik (DIP)</span>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full ml-1">{{ $sectionDIP->count() }} perihal</span>
        </div>
    </div>
    <div class="p-5">
        <div class="overflow-x-auto">
            <table id="tableDIP" class="table-setiap-saat w-full" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-left" style="width:40px;">No</th>
                        <th class="text-left">Perihal</th>
                        <th class="text-center" style="width:90px;">Jenis</th>
                        <th class="text-left">Dokumen / Link</th>
                        <th class="text-center" style="width:90px;">Status</th>
                        <th class="text-left" style="width:120px;">Terakhir Diubah</th>
                        <th class="text-center" style="width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sectionDIP as $idx => $item)
                    @include('ppid.setiap-saat._row', ['idx' => $idx, 'item' => $item])
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     TABEL 2 — Laporan Survei Kepuasan Masyarakat (SKM)
══════════════════════════════════════════════ --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="section-icon" style="background: rgba(16,185,129,0.1);">
            <i class="bi bi-bar-chart-line" style="color:#059669; font-size:0.95rem;"></i>
        </div>
        <div>
            <span class="text-sm font-semibold text-gray-700">Laporan Survei Kepuasan Masyarakat (SKM)</span>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full ml-1">{{ $sectionSKM->count() }} perihal</span>
        </div>
    </div>
    <div class="p-5">
        <div class="overflow-x-auto">
            <table id="tableSKM" class="table-setiap-saat w-full" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-left" style="width:40px;">No</th>
                        <th class="text-left">Perihal</th>
                        <th class="text-center" style="width:90px;">Jenis</th>
                        <th class="text-left">Dokumen / Link</th>
                        <th class="text-center" style="width:90px;">Status</th>
                        <th class="text-left" style="width:120px;">Terakhir Diubah</th>
                        <th class="text-center" style="width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sectionSKM as $idx => $item)
                    @include('ppid.setiap-saat._row', ['idx' => $idx, 'item' => $item])
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     TABEL 3 — Standar Pelayanan
══════════════════════════════════════════════ --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="section-icon" style="background: rgba(139,92,246,0.1);">
            <i class="bi bi-shield-check" style="color:#7c3aed; font-size:0.95rem;"></i>
        </div>
        <div>
            <span class="text-sm font-semibold text-gray-700">Standar Pelayanan</span>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full ml-1">{{ $sectionSP->count() }} perihal</span>
        </div>
    </div>
    <div class="p-5">
        <div class="overflow-x-auto">
            <table id="tableSP" class="table-setiap-saat w-full" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-left" style="width:40px;">No</th>
                        <th class="text-left">Perihal</th>
                        <th class="text-center" style="width:90px;">Jenis</th>
                        <th class="text-left">Dokumen / Link</th>
                        <th class="text-center" style="width:90px;">Status</th>
                        <th class="text-left" style="width:120px;">Terakhir Diubah</th>
                        <th class="text-center" style="width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sectionSP as $idx => $item)
                    @include('ppid.setiap-saat._row', ['idx' => $idx, 'item' => $item])
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>
    const dtOptions = {
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json',
        },
        order: [[0, 'asc']],
        paging: false,
        info: false,
        lengthChange: false,
        searching: false,
        columnDefs: [
            { orderable: false, targets: [2, 3, 6] }, // Jenis, Dokumen/Link, Aksi tidak sortable
        ],
    };

    $(document).ready(function () {
        $('#tableDIP').DataTable(dtOptions);
        $('#tableSKM').DataTable(dtOptions);
        $('#tableSP').DataTable(dtOptions);
    });
</script>
@endpush
