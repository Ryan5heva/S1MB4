@extends('layouts.app')

@section('title', 'Kelola Berita')
@section('page-title', 'Kelola Berita')
@section('page-subtitle', 'Manajemen data berita dan publikasi')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800">Daftar Berita</h3>
        <p class="text-sm text-gray-400 mt-0.5">Total {{ $berita->total() }} berita tersedia</p>
    </div>
    <a href="{{ route('berita.create') }}" class="btn-primary" id="addBeritaBtn">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Berita
    </a>
</div>

<div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">
    <div class="overflow-x-auto">
        <table class="w-full data-table">
            <thead>
                <tr>
                    <th class="text-left w-8">#</th>
                    <th class="text-left">Gambar</th>
                    <th class="text-left">Judul</th>
                    <th class="text-left">Penulis</th>
                    <th class="text-left">Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($berita as $index => $item)
                <tr>
                    <td class="text-gray-400 text-xs">{{ $berita->firstItem() + $index }}</td>
                    <td>
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}"
                                 alt="{{ $item->judul }}"
                                 class="w-16 h-12 object-cover rounded-lg border border-gray-100">
                        @else
                            <div class="w-16 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td>
                        <p class="font-semibold text-gray-800 max-w-xs" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:280px;">
                            {{ $item->judul }}
                        </p>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold text-white flex-shrink-0" style="background: #148F9A;">
                                {{ strtoupper(substr($item->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="text-sm text-gray-600">{{ $item->user->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="text-sm text-gray-500 whitespace-nowrap">{{ $item->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('berita.preview', $item) }}" target="_blank" class="btn-preview" id="previewBerita{{ $item->id }}">Preview</a>
                            <a href="{{ route('berita.edit', $item) }}" class="btn-edit" id="editBerita{{ $item->id }}">Edit</a>
                            @if(Auth::user()->canDelete())
                            <form method="POST" action="{{ route('berita.destroy', $item) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" id="deleteBerita{{ $item->id }}">Hapus</button>
                            </form>
                            @else
                            <button type="button"
                                onclick="alert('Akses Ditolak\n\nHanya Admin atau Super Admin yang dapat menghapus data.\nHubungi admin untuk menghapus berita ini.')"
                                class="btn-danger opacity-60 cursor-not-allowed"
                                id="deleteBerita{{ $item->id }}">Hapus</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-16 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-500">Belum ada berita</p>
                        <p class="text-xs text-gray-400 mt-1">Mulai tambahkan berita pertama Anda</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($berita->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $berita->links() }}
    </div>
    @endif
</div>

@endsection