<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePpidInformasiRequest;
use App\Models\ActivityLog;
use App\Models\PpidInformasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PpidInformasiSertaMertaController extends Controller
{
    /**
     * Tampilkan halaman Informasi Serta Merta dalam satu DataTable.
     *
     * Semua baris bersifat fixed (seeded). Admin hanya mengelola
     * dokumen/link pada setiap Perihal.
     */
    public function index(): View
    {
        $data = PpidInformasi::sertaMerta()
            ->with('user')
            ->orderBy('urutan')
            ->get();

        $total  = $data->count();
        $filled = $data->filter(fn($i) => $i->hasDokumen())->count();

        return view('ppid.serta-merta.index', compact('data', 'total', 'filled'));
    }

    /**
     * Tampilkan form edit dokumen/link untuk satu Perihal.
     *
     * Nama Perihal bersifat readonly — tidak boleh diubah.
     */
    public function edit(PpidInformasi $ppid): View
    {
        // Pastikan item ini memang milik Serta Merta
        abort_if($ppid->jenis_menu !== 'serta_merta', 404);

        $ppid->load('user');
        return view('ppid.serta-merta.edit', compact('ppid'));
    }

    /**
     * Update dokumen/link pada satu Perihal Serta Merta.
     *
     * Yang boleh diubah: deskripsi, jenis, file, url, status, published_at.
     * Nama Perihal (nama_informasi) TIDAK boleh diubah.
     */
    public function update(UpdatePpidInformasiRequest $request, PpidInformasi $ppid): RedirectResponse
    {
        abort_if($ppid->jenis_menu !== 'serta_merta', 404);

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
            $filePath = $request->file('file')->store('ppid/serta-merta', 'public');
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
            'Mengubah dokumen PPID Informasi Serta Merta: "' . $ppid->nama_informasi . '".'
        );

        return redirect()
            ->route('ppid.serta_merta.index')
            ->with('success', 'Dokumen/link "' . Str()->limit($ppid->nama_informasi, 50) . '" berhasil diperbarui.');
    }

    /**
     * API: Kembalikan semua item Informasi Serta Merta untuk web publik.
     */
    public function apiIndex(): \Illuminate\Http\JsonResponse
    {
        $items = PpidInformasi::where('jenis_menu', 'serta_merta')
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
