@extends('layouts.app')

@section('title', 'Jenis Dokumen')
@section('page-title', 'Jenis Dokumen')
@section('page-subtitle', 'Kelola master data kategori dokumen PPID')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

    <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
        <div>
            <h3 class="font-semibold text-gray-800">Daftar Jenis Dokumen</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $jenisDokumen->count() }} jenis dokumen terdaftar</p>
        </div>
        <a href="{{ route('jenis-dokumen.create') }}" class="btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tambah Jenis Dokumen
        </a>
    </div>

    <table class="w-full data-table">
        <thead>
            <tr>
                <th class="text-left">No</th>
                <th class="text-left">Jenis Dokumen</th>
                <th class="text-left">Klasifikasi</th>
                <th class="text-left">Status</th>
                <th class="text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jenisDokumen as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="font-medium text-gray-700">{{ $item->jenis_dokumen }}</td>
                <td>
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-teal-50 text-teal-700">
                        {{ $item->klasifikasi }}
                    </span>
                </td>
                <td>
                    @if($item->isAktif())
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Aktif</span>
                    @else
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Nonaktif</span>
                    @endif
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('jenis-dokumen.edit', $item) }}" class="btn-edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('jenis-dokumen.destroy', $item) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus jenis dokumen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-gray-400 py-8">Belum ada jenis dokumen.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection