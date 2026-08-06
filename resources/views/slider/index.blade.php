@extends('layouts.app')

@section('title', 'Slider')
@section('page-title', 'Slider')
@section('page-subtitle', 'Kelola banner slider untuk halaman publik')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <div>
            <h3 class="font-semibold text-gray-800">Daftar Slider</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $sliders->count() }} banner terdaftar</p>
        </div>
        <a href="{{ route('slider.create') }}" class="btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tambah Slider
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full data-table">
            <thead>
                <tr>
                    <th class="text-left">Gambar</th>
                    <th class="text-left">Judul</th>
                    <th class="text-left">Url Tujuan</th>
                    <th class="text-center">Urutan</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sliders as $slider)
                    <tr>
                        <td>
                            <img src="{{ Storage::url($slider->gambar) }}"
                                 alt="{{ $slider->judul }}"
                                 class="w-24 h-14 object-cover rounded-lg border border-gray-200">
                        </td>
                        <td>
                            <p class="font-medium text-gray-800">{{ $slider->judul }}</p>
                            @if($slider->deskripsi)
                                <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($slider->deskripsi, 60) }}</p>
                            @endif
                        </td>
                        <td>
                            @if($slider->url_tujuan)
                                <a href="{{ $slider->url_tujuan }}" target="_blank" class="text-teal-600 hover:underline text-sm">
                                    {{ Str::limit($slider->url_tujuan, 30) }}
                                </a>
                            @else
                                <span class="text-gray-300 text-sm">—</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $slider->urutan }}</td>
                        <td class="text-center">
                            @if($slider->status)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('slider.edit', $slider) }}" class="btn-edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('slider.destroy', $slider) }}" method="POST"
                                      onsubmit="return confirm('Hapus slider &quot;{{ $slider->judul }}&quot;?');">
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
                        <td colspan="6" class="text-center py-12">
                            <i class="bi bi-images text-gray-300" style="font-size: 2rem;"></i>
                            <p class="text-gray-400 text-sm mt-2">
                                Belum ada slider. <a href="{{ route('slider.create') }}" class="text-teal-600 hover:underline">Tambah sekarang</a>
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection