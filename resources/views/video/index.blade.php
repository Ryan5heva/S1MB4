@extends('layouts.app')

@section('title', 'Kelola Video')
@section('page-title', 'Kelola Video')
@section('page-subtitle', 'Manajemen data video dan publikasi')

@section('content')

{{-- Bagian Judul dan Tombol (Jarak sudah disesuaikan persis seperti Berita) --}}
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800">Daftar Video</h3>
        <p class="text-sm text-gray-500 mt-1">Total {{ $videos->count() }} video tersedia</p>
    </div>
    <a href="{{ route('video.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Video
    </a>
</div>

{{-- Kotak putih (Padding p-6 dihapus dan diganti overflow-hidden agar tabel pas di ujung kotak) --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 font-medium">#</th>
                    <th class="px-6 py-4 font-medium">Judul Video</th>
                    <th class="px-6 py-4 font-medium">Tautan (URL)</th>
                    <th class="px-6 py-4 font-medium">Tanggal</th>
                    <th class="px-6 py-4 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($videos as $index => $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->judul_video }}</td>
                    <td class="px-6 py-4 text-sm text-blue-600">
                        <a href="{{ $item->url_video }}" target="_blank" class="hover:underline">Lihat Video</a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-center space-x-2">
                        <a href="{{ route('video.edit', $item->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">Edit</a>
                        
                        @if(Auth::user()->canDelete())
                        <form action="{{ route('video.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus video ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">Hapus</button>
                        </form>
                        @else
                        <button type="button"
                            onclick="alert('Akses Ditolak\n\nHanya Admin atau Super Admin yang dapat menghapus data.\nHubungi admin untuk menghapus video ini.')"
                            class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-400 rounded-lg opacity-60 cursor-not-allowed">Hapus</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data video.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection