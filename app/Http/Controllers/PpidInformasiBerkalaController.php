<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePpidInformasiRequest;
use App\Http\Requests\UpdatePpidInformasiRequest;
use App\Models\ActivityLog;
use App\Models\PpidInformasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PpidInformasiBerkalaController extends Controller
{
    /**
     * Tampilkan halaman Informasi Berkala dengan semua seksi.
     *
     * Data dikelompokkan per kategori dan diurutkan sesuai urutan kanonical.
     * Setiap baris fixed item selalu ada (seeded); admin hanya mengisi dokumen/link.
     */
    public function index(): View
    {
        // Ambil semua data berkala, kelompokkan per kategori
        $rawData = PpidInformasi::berkala()
            ->with('user')
            ->orderBy('kategori_urutan')
            ->orderBy('urutan')
            ->get()
            ->groupBy('kategori');

        // Susun seksi sesuai urutan kanonical dari model
        $sections = collect(PpidInformasi::KATEGORI_ORDER_BERKALA)
            ->mapWithKeys(fn($k) => [$k => $rawData->get($k, collect())]);

        return view('ppid.berkala.index', compact('sections'));
    }

    /**
     * Tampilkan form tambah item baru (khusus Ketenagakerjaan).
     *
     * Hanya kategori Ketenagakerjaan yang membolehkan penambahan baris baru.
     */
    public function create(): View
    {
        return view('ppid.berkala.create');
    }

    /**
     * Simpan item Ketenagakerjaan baru ke database.
     */
    public function store(StorePpidInformasiRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $filePath = null;
        if ($request->jenis === 'dokumen' && $request->hasFile('file')) {
            $filePath = $request->file('file')->store('ppid', 'public');
        }

        PpidInformasi::create([
            'jenis_menu'      => 'berkala',
            'kategori'        => 'Ketenagakerjaan',
            'kategori_urutan' => 9,
            'nama_informasi'  => $validated['nama_informasi'],
            'deskripsi'       => $validated['deskripsi'] ?? null,
            'jenis'           => $validated['jenis'],
            'file'            => $filePath,
            'url'             => $validated['jenis'] === 'link' ? ($validated['url'] ?? null) : null,
            'status'          => $validated['status'],
            'urutan'          => $validated['urutan'] ?? 0,
            'published_at'    => $validated['published_at'] ?? null,
            'is_fixed'        => false,
            'user_id'         => Auth::id(),
        ]);

        ActivityLog::catat(
            'Tambah Data',
            'Menambahkan PPID Informasi Berkala (Ketenagakerjaan): "' . $validated['nama_informasi'] . '".'
        );

        return redirect()
            ->route('ppid.berkala.index', ['#ketenagakerjaan'])
            ->with('success', 'Data Ketenagakerjaan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit dokumen/link untuk satu baris Informasi Berkala.
     *
     * Nama Informasi bersifat readonly — tidak boleh diubah.
     */
    public function edit(PpidInformasi $ppid): View
    {
        $ppid->load('user');
        return view('ppid.berkala.edit', compact('ppid'));
    }

    /**
     * Update dokumen/link pada baris Informasi Berkala.
     *
     * Yang boleh diubah: deskripsi, jenis, file, url, status, urutan, published_at.
     * Yang TIDAK boleh diubah: nama_informasi, kategori, jenis_menu, is_fixed.
     */
    public function update(UpdatePpidInformasiRequest $request, PpidInformasi $ppid): RedirectResponse
    {
        $validated = $request->validated();

        $filePath = $ppid->file; // pertahankan file lama

        if ($request->jenis === 'dokumen' && $request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($ppid->file && Storage::disk('public')->exists($ppid->file)) {
                Storage::disk('public')->delete($ppid->file);
            }
            $filePath = $request->file('file')->store('ppid', 'public');
        }

        // Jika jenis berubah dari dokumen ke link, hapus file lama
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
            'Mengubah dokumen PPID Informasi Berkala: "' . $ppid->nama_informasi . '".'
        );

        return redirect()
            ->route('ppid.berkala.index')
            ->with('success', 'Dokumen/link "' . $ppid->nama_informasi . '" berhasil diperbarui.');
    }

    /**
     * Hapus item Ketenagakerjaan (non-fixed) dari database.
     *
     * Fixed items tidak dapat dihapus — dilindungi oleh is_fixed check.
     * Hanya Admin dan Super Admin yang dapat menghapus.
     */
    public function destroy(PpidInformasi $ppid): RedirectResponse
    {
        // Guard: item fixed tidak boleh dihapus
        if ($ppid->is_fixed) {
            return redirect()
                ->route('ppid.berkala.index')
                ->with('error', 'Data ini bersifat tetap dan tidak dapat dihapus.');
        }

        // Guard: hanya admin/super admin
        if (! Auth::user()->canDelete()) {
            return redirect()
                ->route('ppid.berkala.index')
                ->with('error', 'Aksi ditolak. Hanya Admin atau Super Admin yang dapat menghapus data.');
        }

        $nama = $ppid->nama_informasi;

        if ($ppid->file && Storage::disk('public')->exists($ppid->file)) {
            Storage::disk('public')->delete($ppid->file);
        }

        $ppid->delete();

        ActivityLog::catat(
            'Hapus Data',
            'Menghapus PPID Informasi Berkala (Ketenagakerjaan): "' . $nama . '".'
        );

        return redirect()
            ->route('ppid.berkala.index')
            ->with('success', 'Data "' . $nama . '" berhasil dihapus.');
    }
}
