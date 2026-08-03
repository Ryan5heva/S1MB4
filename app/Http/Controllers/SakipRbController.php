<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSakipRbRequest;
use App\Http\Requests\UpdateSakipRbRequest;
use App\Models\ActivityLog;
use App\Models\SakipRb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SakipRbController extends Controller
{
    /**
     * Tampilkan daftar dokumen SAKIP-RB, difilter berdasarkan tahun.
     *
     * Query param: ?tahun=xxxx (default: tahun terbaru yang tersedia,
     * atau tahun sekarang jika tabel masih kosong).
     */
    public function index(Request $request): View
    {
        $tahunList  = SakipRb::tahunTersedia();
        $tahunAktif = (int) $request->query('tahun', $tahunList[0] ?? now()->year);

        $items = SakipRb::with('user')
            ->where('tahun', $tahunAktif)
            ->orderBy('id')
            ->get();

        return view('sakip-rb.index', compact('items', 'tahunList', 'tahunAktif'));
    }

    /**
     * Tampilkan form untuk menambah dokumen SAKIP-RB baru.
     */
    public function create(): View
    {
        $tahunList = SakipRb::tahunTersedia();
        return view('sakip-rb.create', compact('tahunList'));
    }

    /**
     * Simpan dokumen SAKIP-RB baru ke database.
     */
    public function store(StoreSakipRbRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $filePath = null;
        if ($request->jenis_input === 'dokumen' && $request->hasFile('file')) {
            $filePath = $request->file('file')->store('sakip-rb', 'public');
        }

        $record = SakipRb::create([
            'jenis_dokumen' => $validated['jenis_dokumen'],
            'klasifikasi'   => $validated['klasifikasi'] ?? null,
            'tahun'         => $validated['tahun'],
            'file'          => $filePath,
            'url'           => $request->jenis_input === 'link' ? ($validated['url'] ?? null) : null,
            'status'        => $validated['status'],
            'user_id'       => Auth::id(),
        ]);

        ActivityLog::catat(
            'Tambah Data',
            'Menambahkan dokumen SAKIP-RB: "' . $record->jenis_dokumen . '" (tahun ' . $record->tahun . ').'
        );

        return redirect()
            ->route('sakip-rb.index', ['tahun' => $record->tahun])
            ->with('success', 'Dokumen "' . Str::limit($record->jenis_dokumen, 50) . '" berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit untuk dokumen SAKIP-RB yang sudah ada.
     */
    public function edit(SakipRb $sakipRb): View
    {
        $sakipRb->load('user');
        $tahunList = SakipRb::tahunTersedia();
        return view('sakip-rb.edit', compact('sakipRb', 'tahunList'));
    }

    /**
     * Update dokumen SAKIP-RB yang sudah ada.
     *
     * Jika upload file baru: hapus file lama, simpan file baru.
     * Jika ganti ke link: hapus file lama, kosongkan kolom file.
     */
    public function update(UpdateSakipRbRequest $request, SakipRb $sakipRb): RedirectResponse
    {
        $validated = $request->validated();

        $filePath = $sakipRb->file; // pertahankan file lama secara default

        if ($request->jenis_input === 'dokumen' && $request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($sakipRb->file && Storage::disk('public')->exists($sakipRb->file)) {
                Storage::disk('public')->delete($sakipRb->file);
            }
            $filePath = $request->file('file')->store('sakip-rb', 'public');
        }

        // Jenis berubah dari dokumen → link: hapus file lama
        if ($request->jenis_input === 'link' && $sakipRb->file) {
            if (Storage::disk('public')->exists($sakipRb->file)) {
                Storage::disk('public')->delete($sakipRb->file);
            }
            $filePath = null;
        }

        $sakipRb->update([
            'jenis_dokumen' => $validated['jenis_dokumen'],
            'klasifikasi'   => $validated['klasifikasi'] ?? null,
            'tahun'         => $validated['tahun'],
            'file'          => $filePath,
            'url'           => $request->jenis_input === 'link' ? ($validated['url'] ?? null) : null,
            'status'        => $validated['status'],
            'user_id'       => Auth::id(),
        ]);

        ActivityLog::catat(
            'Edit Data',
            'Mengubah dokumen SAKIP-RB: "' . $sakipRb->jenis_dokumen . '" (tahun ' . $sakipRb->tahun . ').'
        );

        return redirect()
            ->route('sakip-rb.index', ['tahun' => $sakipRb->tahun])
            ->with('success', 'Dokumen "' . Str::limit($sakipRb->jenis_dokumen, 50) . '" berhasil diperbarui.');
    }

    /**
     * Hapus dokumen SAKIP-RB dari database (dan file storage-nya).
     *
     * Hanya Admin dan Super Admin yang boleh menghapus.
     */
    public function destroy(SakipRb $sakipRb): RedirectResponse
    {
        if (! Auth::user()->canDelete()) {
            return back()->with(
                'error',
                'Aksi ditolak. Hanya Admin atau Super Admin yang dapat menghapus data.'
            );
        }

        $tahun        = $sakipRb->tahun;
        $jenisDokumen = $sakipRb->jenis_dokumen;

        // Hapus file dari storage jika ada
        if ($sakipRb->file && Storage::disk('public')->exists($sakipRb->file)) {
            Storage::disk('public')->delete($sakipRb->file);
        }

        $sakipRb->delete();

        ActivityLog::catat(
            'Hapus Data',
            'Menghapus dokumen SAKIP-RB: "' . $jenisDokumen . '" (tahun ' . $tahun . ').'
        );

        return redirect()
            ->route('sakip-rb.index', ['tahun' => $tahun])
            ->with('success', 'Dokumen "' . Str::limit($jenisDokumen, 50) . '" berhasil dihapus.');
    }
}
