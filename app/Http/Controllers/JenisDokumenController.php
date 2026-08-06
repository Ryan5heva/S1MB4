<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use Illuminate\Http\Request;

class JenisDokumenController extends Controller
{
    /**
     * Tampilkan daftar jenis dokumen.
     */
    public function index()
    {
        $jenisDokumen = JenisDokumen::orderBy('klasifikasi')->orderBy('jenis_dokumen')->get();

        return view('jenis_dokumen.index', compact('jenisDokumen'));
    }

    /**
     * Tampilkan form tambah jenis dokumen.
     */
    public function create()
    {
        return view('jenis_dokumen.create');
    }

    /**
     * Simpan jenis dokumen baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_dokumen' => 'required|string|max:255',
            'klasifikasi'   => 'required|string|max:100',
            'status'        => 'required|in:0,1',
        ]);

        JenisDokumen::create($validated);

        return redirect()
            ->route('jenis-dokumen.index')
            ->with('success', 'Jenis dokumen berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit jenis dokumen.
     */
    public function edit(JenisDokumen $jenisDokumen)
    {
        return view('jenis_dokumen.edit', compact('jenisDokumen'));
    }

    /**
     * Perbarui jenis dokumen.
     */
    public function update(Request $request, JenisDokumen $jenisDokumen)
    {
        $validated = $request->validate([
            'jenis_dokumen' => 'required|string|max:255',
            'klasifikasi'   => 'required|string|max:100',
            'status'        => 'required|in:0,1',
        ]);

        $jenisDokumen->update($validated);

        return redirect()
            ->route('jenis-dokumen.index')
            ->with('success', 'Jenis dokumen berhasil diperbarui.');
    }

    /**
     * Hapus jenis dokumen.
     */
    public function destroy(JenisDokumen $jenisDokumen)
    {
        $isUsed = $jenisDokumen->ppidInformasi()->exists();

        if ($isUsed) {
            return redirect()
                ->route('jenis-dokumen.index')
                ->with('error', 'Jenis dokumen tidak bisa dihapus karena masih digunakan pada data PPID.');
        }

        $jenisDokumen->delete();

        return redirect()
            ->route('jenis-dokumen.index')
            ->with('success', 'Jenis dokumen berhasil dihapus.');
    }
}