<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSliderRequest;
use App\Models\ActivityLog;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SliderController extends Controller
{
    /**
     * Tampilkan galeri semua gambar slider, urut berdasarkan urutan asc.
     * Setiap gambar punya checkbox status (tampil/tidak di web publik).
     */
    public function index(): View
    {
        $sliders = Slider::orderBy('urutan')->orderBy('id')->get();

        return view('slider.index', compact('sliders'));
    }

    /**
     * Tampilkan form upload gambar baru ke galeri.
     */
    public function create(): View
    {
        return view('slider.create');
    }

    /**
     * Simpan gambar baru ke galeri.
     * Default status = nonaktif (belum dicentang), admin centang manual dari galeri.
     */
    public function store(StoreSliderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $gambarPath = $request->file('gambar')->store('slider', 'public');

        Slider::create([
            'url_tujuan' => $validated['url_tujuan'] ?? null,
            'gambar'     => $gambarPath,
            'urutan'     => $validated['urutan'] ?? 0,
            'status'     => false,
            'user_id'    => Auth::id(),
        ]);

        ActivityLog::catat('Tambah Data', 'Menambahkan gambar baru ke galeri slider.');

        return redirect()
            ->route('slider.index')
            ->with('success', 'Gambar berhasil ditambahkan ke galeri. Centang gambar untuk menampilkannya di web.');
    }

    /**
     * Perbarui data slider yang sudah ada.
     * Gambar baru bersifat opsional — jika tidak diunggah, gambar lama dipertahankan.
     */
    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $validated = $request->validate([
            'url_tujuan' => ['nullable', 'url', 'max:255'],
            'gambar'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'urutan'     => ['nullable', 'integer', 'min:0'],
            'status'     => ['required', 'boolean'],
        ]);

        // Ganti gambar jika ada file baru yang diunggah
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari storage
            if ($slider->gambar && Storage::disk('public')->exists($slider->gambar)) {
                Storage::disk('public')->delete($slider->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('slider', 'public');
        }

        $slider->update([
            'url_tujuan' => $validated['url_tujuan'] ?? null,
            'gambar'     => $validated['gambar'] ?? $slider->gambar,
            'urutan'     => $validated['urutan'] ?? 0,
            'status'     => $validated['status'],
        ]);

        ActivityLog::catat('Edit Data', 'Memperbarui data slider (ID: ' . $slider->id . ').');

        return redirect()
            ->route('slider.index')
            ->with('success', 'Slider berhasil diperbarui.');
    }

    /**
     * Toggle status tampil/tidak untuk satu gambar (dipanggil saat checkbox diklik).
     */
    public function toggleStatus(Slider $slider): RedirectResponse
    {
        $slider->update(['status' => ! $slider->status]);

        ActivityLog::catat(
            'Edit Data',
            ($slider->status ? 'Menampilkan' : 'Menyembunyikan') . ' gambar slider (ID: ' . $slider->id . ') di web publik.'
        );

        return redirect()
            ->route('slider.index')
            ->with('success', 'Status tampil gambar berhasil diperbarui.');
    }

    /**
     * Hapus gambar dari galeri secara permanen.
     */
    public function destroy(Slider $slider): RedirectResponse
    {
        if ($slider->gambar && Storage::disk('public')->exists($slider->gambar)) {
            Storage::disk('public')->delete($slider->gambar);
        }

        $slider->delete();

        ActivityLog::catat('Hapus Data', 'Menghapus gambar slider (ID: ' . $slider->id . ') dari galeri.');

        return redirect()
            ->route('slider.index')
            ->with('success', 'Gambar berhasil dihapus dari galeri.');
    }
}