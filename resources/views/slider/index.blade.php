@extends('layouts.app')

@section('title', 'Slider')
@section('page-title', 'Slider')
@section('page-subtitle', 'Pilih gambar yang ingin ditampilkan di halaman depan')

@section('content')
<div class="bg-white rounded-xl border border-gray-100 overflow-hidden" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <div>
            <h3 class="font-semibold text-gray-800">Galeri Slider</h3>
            <p class="text-xs text-gray-400 mt-0.5">
                {{ $sliders->count() }} gambar tersimpan &bull;
                {{ $sliders->where('status', true)->count() }} sedang tampil di web
            </p>
        </div>
        <a href="{{ route('slider.create') }}" class="btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tambah Gambar
        </a>
    </div>

    {{-- Petunjuk singkat --}}
    <div class="mx-6 mt-4 px-4 py-3 rounded-lg bg-teal-50 border border-teal-100 flex items-start gap-2">
        <i class="bi bi-info-circle text-teal-600 mt-0.5"></i>
        <p class="text-xs text-teal-700">
            Centang gambar untuk menampilkannya di halaman depan website. Gambar yang tidak dicentang tetap tersimpan di galeri, tapi tidak muncul di web.
        </p>
    </div>

    {{-- Konten --}}
    <div class="p-6">
        @if($sliders->count() === 0)

            {{-- Empty state --}}
            <div class="text-center py-12">
                <i class="bi bi-images text-gray-300" style="font-size: 2rem;"></i>
                <p class="text-gray-400 text-sm mt-2">
                    Belum ada gambar. <a href="{{ route('slider.create') }}" class="text-teal-600 hover:underline">Tambah sekarang</a>
                </p>
            </div>

        @else

            {{-- Grid galeri --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($sliders as $slider)
                    <div class="border rounded-xl overflow-hidden relative {{ $slider->status ? 'border-teal-400 ring-2 ring-teal-100' : 'border-gray-200' }}">

                        {{-- Badge status --}}
                        <div class="absolute top-2 right-2 z-10">
                            @if($slider->status)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-teal-500 text-white shadow">
                                    <i class="bi bi-eye-fill"></i> Tampil
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-700/70 text-white shadow">
                                    <i class="bi bi-eye-slash-fill"></i> Tersembunyi
                                </span>
                            @endif
                        </div>

                        <img src="{{ Storage::url($slider->gambar) }}"
                             alt="Slider {{ $slider->id }}"
                             class="w-full h-40 object-cover">

                        <div class="p-3 flex items-center justify-between bg-gray-50 gap-2">

                            {{-- Checkbox toggle status (auto-submit saat diklik) --}}
                            <form action="{{ route('slider.toggle-status', $slider) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox"
                                           onchange="this.form.submit()"
                                           {{ $slider->status ? 'checked' : '' }}
                                           class="w-4 h-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                    <span class="text-xs font-medium text-gray-600">Tampilkan</span>
                                </label>
                            </form>

                            <form action="{{ route('slider.destroy', $slider) }}" method="POST"
                                  onsubmit="return confirm('Hapus gambar ini secara permanen dari galeri?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

        @endif
    </div>
</div>
@endsection