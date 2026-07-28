<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePpidInformasiRequest;
use App\Models\ActivityLog;
use App\Models\PpidInformasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PpidInformasiDikecualikanController extends Controller
{
    /**
     * Tampilkan halaman Informasi Dikecualikan dalam satu DataTable.
     *
     * Semua baris bersifat fixed (seeded). Admin hanya mengelola
     * dokumen/link pada setiap Perihal.
     */
    public function index(): View
    {
        $data = PpidInformasi::dikecualikan()
            ->with('user')
            ->orderBy('urutan')
            ->get();

        $total  = $data->count();
        $filled = $data->filter(fn($i) => $i->hasDokumen())->count();

        return view('ppid.dikecualikan.index', compact('data', 'total', 'filled'));
    }

    /**
     * Tampilkan form edit dokumen/link untuk satu Perihal Dikecualikan.
     *
     * Nama Perihal bersifat readonly — tidak boleh diubah.
     */
    public function edit(PpidInformasi $ppid): View
    {
        // Pastikan item ini memang milik Dikecualikan
        abort_if($ppid->jenis_menu !== 'dikecualikan', 404);

        $ppid->load('user');
        return view('ppid.dikecualikan.edit', compact('ppid'));
    }

    /**
     * Update dokumen/link pada satu Perihal Dikecualikan.
     *
     * Yang boleh diubah: deskripsi, jenis, file, url, status, published_at.
     * Nama Perihal (nama_informasi) TIDAK boleh diubah.
     */
    public function update(UpdatePpidInformasiRequest $request, PpidInformasi $ppid): RedirectResponse
    {
        abort_if($ppid->jenis_menu !== 'dikecualikan', 404);

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
            $filePath = $request->file('file')->store('ppid/dikecualikan', 'public');
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
            'Mengubah dokumen PPID Informasi Dikecualikan: "' . $ppid->nama_informasi . '".'
        );

        return redirect()
            ->route('ppid.dikecualikan.index')
            ->with('success', 'Dokumen/link "' . Str::limit($ppid->nama_informasi, 50) . '" berhasil diperbarui.');
    }
}
