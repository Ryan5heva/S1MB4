@extends('layouts.app')

@section('title', 'PPID — Informasi Dikecualikan')
@section('page-title', 'Informasi Dikecualikan')
@section('page-subtitle', 'Pengelolaan dokumen & tautan PPID — Informasi yang dikecualikan dari akses publik')

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
    #tableDikecualikan thead th {
        background: #f8fafc; font-size: 0.7rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;
        padding: 0.75rem 1rem; white-space: nowrap;
    }
    #tableDikecualikan tbody td {
        padding: 0.75rem 1rem; font-size: 0.8125rem;
        color: #374151; vertical-align: middle;
    }
    #tableDikecualikan tbody tr:hover td { background: #fafafa; }
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

{{-- Tabel --}}
<div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.04);">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
        <i class="bi bi-lock" style="color:#148F9A;"></i>
        <span class="text-sm font-semibold text-gray-700">Informasi Dikecualikan</span>
        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full ml-1">{{ $total }} perihal</span>
    </div>
    <div class="p-5">
        <div class="overflow-x-auto">
            <table id="tableDikecualikan" class="w-full" style="width:100%">
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
                    @foreach($data as $idx => $item)
                    <tr>
                        {{-- No --}}
                        <td class="text-gray-400 text-xs text-center">{{ $idx + 1 }}</td>

                        {{-- Perihal --}}
                        <td>
                            <p class="font-medium text-gray-800" style="font-size:0.8125rem; max-width:350px; line-height:1.45;">
                                {{ $item->nama_informasi }}
                            </p>
                            @if($item->deskripsi)
                                <p class="text-xs text-gray-400 mt-0.5 leading-snug" style="max-width:350px;">
                                    {{ Str::limit($item->deskripsi, 70) }}
                                </p>
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
                            @if($item->hasDokumen())
                                <span class="text-xs text-gray-600 block">{{ $item->updated_at->format('d M Y') }}</span>
                                <span class="text-xs text-gray-400">{{ $item->updated_at->format('H:i') }}</span>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="text-center">
                            @if($item->hasDokumen())
                                <a href="{{ route('ppid.dikecualikan.edit', $item) }}"
                                   class="btn-edit" style="padding:0.3rem 0.6rem; font-size:0.75rem;"
                                   id="editDikecualikan{{ $item->id }}" title="Edit Dokumen/Link">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            @else
                                <a href="{{ route('ppid.dikecualikan.edit', $item) }}"
                                   class="btn-primary" style="padding:0.3rem 0.6rem; font-size:0.75rem;"
                                   id="tambahDikecualikan{{ $item->id }}" title="Tambah Dokumen/Link">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </a>
                            @endif
                        </td>
                    </tr>
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
    $(document).ready(function () {
        $('#tableDikecualikan').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json',
            },
            order: [[0, 'asc']],
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [2, 3, 6] }, // Jenis, Dokumen/Link, Aksi tidak sortable
            ],
        });
    });
</script>
@endpush
