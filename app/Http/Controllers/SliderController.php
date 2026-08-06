<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Models\ActivityLog;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SliderController extends Controller
{
    /**
     * Tampilkan daftar semua slider, urut berdasarkan urutan asc.
     */
    public function index(): View
    {
        $sliders = Slider::orderBy('urutan')->orderBy('id')->get();

        return view('slider.index', compact('sliders'));
    }

    /**
     * Tampilkan form tambah slider baru.
     */
    public function create(): View
    {
        return view('slider.create');
    }

    /**
     * Simpan slider baru ke database.
     */
    public function store(StoreSliderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $gambarPath = $request->file('gambar')->store('slider', 'public');

        Slider::create([
            'judul'      => $validated['judul'],
            'deskripsi'  => $validated['deskripsi'] ?? null,
            'gambar'     => $gambarPath,
            'url_tujuan' => $validated['url_tujuan'] ?? null,
            'urutan'     => $validated['urutan'] ?? 0,
            'status'     => $validated['status'],
            'user_id'    => Auth::id(),
        ]);

        ActivityLog::catat(
            'Tambah Data',
            'Menambahkan slider: "' . $validated['judul'] . '".'
        );

        return redirect()
            ->route('slider.index')
            ->with('success', 'Slider "' . $validated['judul'] . '" berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit slider.
     */
    public function edit(Slider $slider): View
    {
        return view('slider.edit', compact('slider'));
    }

    /**
     * Update slider di database.
     */
    public function update(UpdateSliderRequest $request, Slider $slider): RedirectResponse
    {
        $validated = $request->validated();

        $gambarPath = $slider->gambar; // pertahankan gambar lama jika tidak upload baru

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($slider->gambar && Storage::disk('public')->exists($slider->gambar)) {
                Storage::disk('public')->delete($slider->gambar);
            }
            $gambarPath = $request->file('gambar')->store('slider', 'public');
        }

        $slider->update([
            'judul'      => $validated['judul'],
            'deskripsi'  => $validated['deskripsi'] ?? null,
            'gambar'     => $gambarPath,
            'url_tujuan' => $validated['url_tujuan'] ?? null,
            'urutan'     => $validated['urutan'] ?? $slider->urutan,
            'status'     => $validated['status'],
            'user_id'    => Auth::id(),
        ]);

        ActivityLog::catat(
            'Edit Data',
            'Mengubah slider: "' . $slider->judul . '".'
        );

        return redirect()
            ->route('slider.index')
            ->with('success', 'Slider "' . $slider->judul . '" berhasil diperbarui.');
    }

    /**
     * Hapus slider dari database.
     */
    public function destroy(Slider $slider): RedirectResponse
    {
        $judul = $slider->judul;

        if ($slider->gambar && Storage::disk('public')->exists($slider->gambar)) {
            Storage::disk('public')->delete($slider->gambar);
        }

        $slider->delete();

        ActivityLog::catat(
            'Hapus Data',
            'Menghapus slider: "' . $judul . '".'
        );

        return redirect()
            ->route('slider.index')
            ->with('success', 'Slider "' . $judul . '" berhasil dihapus.');
    }
}