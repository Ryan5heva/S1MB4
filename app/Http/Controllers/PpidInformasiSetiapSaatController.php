<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePpidInformasiRequest;
use App\Models\ActivityLog;
use App\Models\PpidInformasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PpidInformasiSetiapSaatController extends Controller
{
    /**
     * Tampilkan halaman Informasi Setiap Saat dalam 3 tabel terpisah per kategori.
     *
     * Semua baris bersifat fixed (seeded). Admin hanya mengelola
     * dokumen/link pada setiap Perihal.
     */
    public function index(): View
    {
        // Ambil semua data setiap_saat, kelompokkan per kategori
        $rawData = PpidInformasi::setiapSaat()
            ->with('user')
            ->orderBy('kategori_urutan')
            ->orderBy('urutan')
            ->get()
            ->groupBy('kategori');

        // Susun seksi sesuai urutan kanonical dari model
        $sections = collect(PpidInformasi::KATEGORI_ORDER_SETIAP_SAAT)
            ->mapWithKeys(fn($k) => [$k => $rawData->get($k, collect())]);

        return view('ppid.setiap-saat.index', compact('sections'));
    }

    /**
     * Tampilkan form edit dokumen/link untuk satu Perihal Setiap Saat.
     *
     * Nama Perihal bersifat readonly — tidak boleh diubah.
     */
    public function edit(PpidInformasi $ppid): View
    {
        // Pastikan item ini memang milik Setiap Saat
        abort_if($ppid->jenis_menu !== 'setiap_saat', 404);

        $ppid->load('user');
        return view('ppid.setiap-saat.edit', compact('ppid'));
    }

    /**
     * Update dokumen/link pada satu Perihal Setiap Saat.
     *
     * Yang boleh diubah: deskripsi, jenis, file, url, status, published_at.
     * Nama Perihal (nama_informasi) TIDAK boleh diubah.
     */
    public function update(UpdatePpidInformasiRequest $request, PpidInformasi $ppid): RedirectResponse
    {
        abort_if($ppid->jenis_menu !== 'setiap_saat', 404);

        $validated = $request->validated();

        // Validasi: file wajib jika belum ada dokumen sebelumnya dan jenis = dokumen
        if ($request->jenis === 'dokumen' && !$ppid->file && !$request->hasFile('file')) {
            return back()
                ->withInput()
                ->withErrors(['file' => 'File dokumen wajib diunggah karena belum ada dokumen sebelumnya.']);
        }

        $filePath = $ppid->file; // pertahankan file lama secara default

        if ($request->jenis === 'dokumen' && $request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($ppid->file && Storage::disk('public')->exists($ppid->file)) {
                Storage::disk('public')->delete($ppid->file);
            }
            $filePath = $request->file('file')->store('ppid/setiap-saat', 'public');
        }

        // Jenis berubah dari dokumen → link: hapus file lama
        if ($request->jenis === 'link' && $ppid->file) {
            if (Storage::disk('public')->exists($ppid->file)) {
                Storage::disk('public')->delete($ppid->file);
            }
            $filePath = null;
        }

        $ppid->update([
            'deskripsi'    => $validated['deskripsi'] ?? null,
            'jenis'        => $validated['jenis'],
            'file'         => $filePath,
            'url'          => $validated['jenis'] === 'link' ? ($validated['url'] ?? null) : null,
            'status'       => $validated['status'],
            'urutan'       => $validated['urutan'] ?? $ppid->urutan,
            'published_at' => $validated['published_at'] ?? null,
            'user_id'      => Auth::id(),
        ]);

        ActivityLog::catat(
            'Edit Data',
            'Mengubah dokumen PPID Informasi Setiap Saat: "' . $ppid->nama_informasi . '".'
        );

        return redirect()
            ->route('ppid.setiap_saat.index')
            ->with('success', 'Dokumen/link "' . \Illuminate\Support\Str::limit($ppid->nama_informasi, 50) . '" berhasil diperbarui.');
    }

    /**
     * API: Kembalikan semua item Informasi Setiap Saat untuk web publik.
     */
    public function apiIndex(): \Illuminate\Http\JsonResponse
    {
        $items = PpidInformasi::where('jenis_menu', 'setiap_saat')
            ->where('status', true)
            ->orderBy('urutan')
            ->get()
            ->map(fn($i) => [
                'id'             => $i->id,
                'nama_informasi' => $i->nama_informasi,
                'deskripsi'      => $i->deskripsi,
                'jenis'          => $i->jenis,
                'file'           => $i->file ? asset('storage/' . $i->file) : null,
                'url'            => $i->url,
                'published_at'   => $i->published_at?->format('Y-m-d'),
            ]);

        return response()->json($items);
    }
}
