@extends('layouts.app')

@section('title', 'SAKIP-RB — Dokumen Tahun ' . $tahunAktif)
@section('page-title', 'SAKIP-RB')
@section('page-subtitle', 'Pengelolaan dokumen SAKIP (Sistem Akuntabilitas Kinerja Instansi Pemerintah) dan Reformasi Birokrasi')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<style>
    /* ── Badge ── */
    .badge {
        display: inline-flex; align-items: center; gap: 0.25rem;
        font-size: 0.7rem; font-weight: 600;
        padding: 0.175rem 0.55rem; border-radius: 9999px; white-space: nowrap;
    }
    .badge-aktif    { background: #dcfce7; color: #15803d; }
    .badge-nonaktif { background: #f1f5f9; color: #64748b; }

    /* ── DataTables override ── */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db; border-radius: 0.5rem;
        padding: 0.375rem 0.75rem; font-size: 0.8125rem;
        outline: none; margin-left: 0.5rem; transition: border-color .2s, box-shadow .2s;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #148F9A; box-shadow: 0 0 0 3px rgba(20,143,154,.12);
    }

    /* ── Table ── */
    #tableSakipRb thead th {
        background: #f8fafc; font-size: 0.7rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;
        padding: 0.75rem 1rem; white-space: nowrap;
    }
    #tableSakipRb tbody td {
        padding: 0.75rem 1rem; font-size: 0.8125rem;
        color: #374151; vertical-align: middle;
    }
    #tableSakipRb tbody tr:hover td { background: #fafafa; }
</style>
@endpush

@section('content')

{{-- Flash Messages (in-page, selain yang di layout) --}}
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

{{-- Header: Filter Tahun + Tambah --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    {{-- Dropdown Filter Tahun --}}
    <div class="flex items-center gap-2">
        <label for="filterTahun" class="text-sm font-medium text-gray-600">Tahun:</label>
        <select id="filterTahun"
                class="form-input"
                style="width:auto; padding:0.375rem 0.75rem; font-size:0.875rem;"
                onchange="window.location.href='{{ route('sakip-rb.index') }}?tahun=' + this.value">
            @foreach($tahunList as $tahun)
                <option value="{{ $tahun }}" {{ $tahun === $tahunAktif ? 'selected' : '' }}>
                    {{ $tahun }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Tombol Tambah Data --}}
    <a href="{{ route('sakip-rb.create') }}" class="btn-primary" id="addSakipRbBtn">
        <i class="bi bi-plus-lg"></i> Tambah Data
    </a>
</div>

{{-- Tabel Dokumen --}}
<div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.04);">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
        <i class="bi bi-clipboard-check" style="color:#148F9A;"></i>
        <span class="text-sm font-semibold text-gray-700">Dokumen SAKIP-RB Tahun {{ $tahunAktif }}</span>
        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full ml-1">{{ $items->count() }} dokumen</span>
    </div>
    <div class="p-5">
        <div class="overflow-x-auto">
            <table id="tableSakipRb" class="w-full" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-left" style="width:40px;">No</th>
                        <th class="text-left">Dokumen</th>
                        <th class="text-left">Tautan</th>
                        <th class="text-center" style="width:90px;">Status</th>
                        <th class="text-left" style="width:120px;">Terakhir Diubah</th>
                        <th class="text-center" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $idx => $item)
                    <tr>
                        {{-- No --}}
                        <td class="text-gray-400 text-xs text-center">{{ $idx + 1 }}</td>

                        {{-- Dokumen --}}
                        <td>
                            <p class="font-medium text-gray-800" style="font-size:0.8125rem; line-height:1.45;">
                                {{ $item->jenis_dokumen }}
                            </p>
                            @if($item->klasifikasi)
                                <p class="text-xs text-gray-400 mt-0.5 leading-snug">
                                    {{ $item->klasifikasi }}
                                </p>
                            @endif
                        </td>

                        {{-- Tautan --}}
                        <td>
                            @if($item->file)
                                <a href="{{ asset('storage/' . $item->file) }}" target="_blank"
                                   class="inline-flex items-center gap-1 text-xs text-teal-600 hover:underline"
                                   title="{{ $item->file_name }}">
                                    <i class="bi bi-download"></i>
                                    {{ Str::limit($item->file_name, 32) }}
                                </a>
                            @elseif($item->url)
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
                            @if($item->status === '1')
                                <span class="badge badge-aktif"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Aktif</span>
                            @else
                                <span class="badge badge-nonaktif"><i class="bi bi-circle" style="font-size:.4rem;"></i> Nonaktif</span>
                            @endif
                        </td>

                        {{-- Terakhir Diubah --}}
                        <td>
                            @if($item->updated_at)
                                <span class="text-xs text-gray-600 block">{{ $item->updated_at->format('d M Y') }}</span>
                                <span class="text-xs text-gray-400">{{ $item->updated_at->format('H:i') }}</span>
                                @if($item->user)
                                    <span class="text-xs text-gray-300 block mt-0.5">{{ $item->user->name }}</span>
                                @endif
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Edit --}}
                                <a href="{{ route('sakip-rb.edit', $item) }}"
                                   class="btn-edit" style="padding:0.3rem 0.6rem; font-size:0.75rem;"
                                   id="editSakipRb{{ $item->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                {{-- Hapus — hanya Admin & Super Admin --}}
                                @if(Auth::user()->canDelete())
                                    <button type="button"
                                        class="btn-danger" style="padding:0.3rem 0.5rem; font-size:0.75rem;"
                                        id="delSakipRb{{ $item->id }}"
                                        title="Hapus"
                                        onclick="konfirmasiHapus({{ $item->id }}, '{{ addslashes($item->jenis_dokumen) }}')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                    <form id="formDelete{{ $item->id }}"
                                          method="POST"
                                          action="{{ route('sakip-rb.destroy', $item) }}"
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
                        <td colspan="6" class="text-center py-10 text-gray-400">
                            <i class="bi bi-file-earmark-plus" style="font-size:2rem;color:#d1d5db;display:block;margin-bottom:.5rem;"></i>
                            <p class="text-sm font-medium text-gray-500">Belum ada dokumen untuk tahun {{ $tahunAktif }}</p>
                            <p class="text-xs mt-1">Klik <strong>Tambah Data</strong> untuk menambahkan dokumen baru.</p>
                        </td>
                    </tr>
                    @endforelse
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        if ($('#tableSakipRb tbody tr td').length > 1) {
            $('#tableSakipRb').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json',
                },
                paging: false,
                info: false,
                lengthChange: false,
                searching: false,
                columnDefs: [
                    { orderable: false, targets: [2, 5] }, // Tautan, Aksi tidak sortable
                ],
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
