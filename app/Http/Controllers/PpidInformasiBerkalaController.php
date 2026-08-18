<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePpidInformasiRequest;
use App\Http\Requests\UpdatePpidInformasiRequest;
use App\Models\ActivityLog;
use App\Models\JenisDokumen;
use App\Models\PpidInformasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PpidInformasiBerkalaController extends Controller
{
    /**
     * Tampilkan halaman PPID dengan satu tabel dinamis.
     *
     * Kategori dipilih via dropdown (query string ?jenis_dokumen_id=X).
     * Default: kategori pertama dari tabel jenis_dokumen (id terkecil).
     */
    public function index(Request $request): View
    {
        // Tampilkan semua jenis dokumen aktif di dropdown (bukan hanya 'Berkala')
        // Kategori seperti SAKIP juga dikelola dari halaman ini, namun ditampilkan
        // di frontend sesuai klasifikasinya (mis. halaman /sakip-rb untuk SAKIP)
        $jenisDokumenList = JenisDokumen::where('status', '1')->orderBy('grup')->orderBy('jenis_dokumen')->get();

        // Kelompokkan per grup untuk <optgroup> pada dropdown kategori
        $grupKategori = $jenisDokumenList->groupBy('grup');

        // Tentukan id kategori aktif (dari query string atau default ke yang pertama)
        $defaultId        = $jenisDokumenList->first()?->id;
        $jenisDokumenId   = (int) $request->query('jenis_dokumen_id', $defaultId);

        // Kategori yang sedang aktif (untuk label judul & cek is_fixed)
        $jenisDokumenAktif = JenisDokumen::find($jenisDokumenId);

        // Ambil semua item untuk kategori (jenis_dokumen) yang dipilih
        // Tidak lagi filter hanya jenis_menu='berkala' karena kategori non-Berkala
        // (mis. SAKIP) juga diinput melalui halaman ini
        $items = PpidInformasi::with('user')
            ->where('id_jenis_dokumen', $jenisDokumenId)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        return view('ppid.berkala.index', compact('jenisDokumenList', 'grupKategori', 'jenisDokumenAktif', 'items'));
    }

    /**
     * Tampilkan form tambah item baru.
     *
     * Kategori dipilih dari dropdown jenis_dokumen.
     * Query string ?jenis_dokumen_id=X untuk pra-pilih kategori tertentu.
     */
    public function create(Request $request): View
    {
        // Tampilkan semua jenis dokumen aktif — termasuk SAKIP dan lainnya
        $jenisDokumenList = JenisDokumen::where('status', '1')->orderBy('grup')->orderBy('jenis_dokumen')->get();
        $jenisDokumenId   = (int) $request->query('jenis_dokumen_id', $jenisDokumenList->first()?->id);

        // Kelompokkan per grup untuk <optgroup> pada dropdown kategori
        $grupKategori = $jenisDokumenList->groupBy('grup');

        return view('ppid.berkala.create', compact('jenisDokumenList', 'grupKategori', 'jenisDokumenId'));
    }

    /**
     * Simpan item PPID baru ke database.
     */
    public function store(StorePpidInformasiRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $filePath = null;
        if ($request->jenis === 'dokumen' && $request->hasFile('file')) {
            $filePath = $request->file('file')->store('ppid', 'public');
        }

        // Tentukan jenis_menu berdasarkan klasifikasi jenis_dokumen yang dipilih.
        // SAKIP dan kategori lain yang bukan 'berkala' tetap disimpan dengan
        // jenis_menu='berkala' (enum constraint) — halaman publik SAKIP sudah
        // membaca via id_jenis_dokumen, bukan jenis_menu, sehingga tetap terpisah.
        // Jenis menu yang memiliki enum tersendiri (serta_merta, setiap_saat, dll)
        // tetap diatur via halaman admin masing-masing, bukan via form ini.
        $jenisDokumen = JenisDokumen::find($validated['id_jenis_dokumen']);
        $kategoriNama = $jenisDokumen?->jenis_dokumen ?? '-';

        $item = PpidInformasi::create([
            'jenis_menu'       => 'berkala',
            'id_jenis_dokumen' => $validated['id_jenis_dokumen'],
            'nama_informasi'   => $validated['nama_informasi'],
            'deskripsi'        => $validated['deskripsi'] ?? null,
            'jenis'            => $validated['jenis'],
            'file'             => $filePath,
            'url'              => $validated['jenis'] === 'link' ? ($validated['url'] ?? null) : null,
            'status'           => $validated['status'],
            'urutan'           => $validated['urutan'] ?? 0,
            'tahun'            => $validated['tahun'] ?? null,
            'published_at'     => $validated['published_at'] ?? null,
            'is_fixed'         => false,
            'user_id'          => Auth::id(),
        ]);

        ActivityLog::catat(
            'Tambah Data',
            'Menambahkan PPID Informasi Berkala (' . $kategoriNama . '): "' . $validated['nama_informasi'] . '".'
        );

        return redirect()
            ->route('ppid.berkala.index', ['jenis_dokumen_id' => $validated['id_jenis_dokumen']])
            ->with('success', 'Data "' . $validated['nama_informasi'] . '" berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit dokumen/link untuk satu baris Informasi Berkala.
     *
     * Nama Informasi bersifat readonly — tidak boleh diubah.
     */
    public function edit(PpidInformasi $ppid): View
    {
        $ppid->load(['user', 'jenisDokumen']);
        // Tampilkan semua jenis dokumen aktif — termasuk SAKIP dan lainnya
        $jenisDokumenList = JenisDokumen::where('status', '1')->orderBy('grup')->orderBy('jenis_dokumen')->get();

        // Kelompokkan per grup untuk <optgroup> pada dropdown kategori
        $grupKategori = $jenisDokumenList->groupBy('grup');

        return view('ppid.berkala.edit', compact('ppid', 'jenisDokumenList', 'grupKategori'));
    }

    /**
     * Update dokumen/link pada baris Informasi Berkala.
     *
     * Yang boleh diubah: deskripsi, jenis, file, url, status, urutan, tahun, published_at.
     * Yang TIDAK boleh diubah: nama_informasi, id_jenis_dokumen (jika fixed), jenis_menu, is_fixed.
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
            'tahun'        => $validated['tahun'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'user_id'      => Auth::id(),
        ]);

        ActivityLog::catat(
            'Edit Data',
            'Mengubah dokumen PPID Informasi Berkala: "' . $ppid->nama_informasi . '".'
        );

        $jenisDokumenId = $ppid->id_jenis_dokumen;

        return redirect()
            ->route('ppid.berkala.index', ['jenis_dokumen_id' => $jenisDokumenId])
            ->with('success', 'Dokumen/link "' . $ppid->nama_informasi . '" berhasil diperbarui.');
    }

    /**
     * Hapus item PPID (non-fixed) dari database.
     *
     * Fixed items tidak dapat dihapus — dilindungi oleh is_fixed check.
     * Hanya Admin dan Super Admin yang dapat menghapus.
     */
    public function destroy(PpidInformasi $ppid): RedirectResponse
    {
        $jenisDokumenId = $ppid->id_jenis_dokumen;

        // Guard: item fixed tidak boleh dihapus
        if ($ppid->is_fixed) {
            return redirect()
                ->route('ppid.berkala.index', ['jenis_dokumen_id' => $jenisDokumenId])
                ->with('error', 'Data ini bersifat tetap dan tidak dapat dihapus.');
        }

        // Guard: hanya admin/super admin
        if (! Auth::user()->canDelete()) {
            return redirect()
                ->route('ppid.berkala.index', ['jenis_dokumen_id' => $jenisDokumenId])
                ->with('error', 'Aksi ditolak. Hanya Admin atau Super Admin yang dapat menghapus data.');
        }

        $nama = $ppid->nama_informasi;
        $kategoriNama = $ppid->jenisDokumen?->jenis_dokumen ?? '-';

        if ($ppid->file && Storage::disk('public')->exists($ppid->file)) {
            Storage::disk('public')->delete($ppid->file);
        }

        $ppid->delete();

        ActivityLog::catat(
            'Hapus Data',
            'Menghapus PPID Informasi Berkala (' . $kategoriNama . '): "' . $nama . '".'
        );

        return redirect()
            ->route('ppid.berkala.index', ['jenis_dokumen_id' => $jenisDokumenId])
            ->with('success', 'Data "' . $nama . '" berhasil dihapus.');
    }

    /**
     * API: Kembalikan semua item PPID Berkala yang aktif untuk web publik.
     * Dikelompokkan berdasarkan jenis_dokumen (kategori).
     */
    public function apiIndex(): \Illuminate\Http\JsonResponse
    {
        $items = PpidInformasi::with('jenisDokumen')
            ->where('jenis_menu', 'berkala')
            ->where('status', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get()
            ->map(fn($i) => [
                'id'             => $i->id,
                'nama_informasi' => $i->nama_informasi,
                'deskripsi'      => $i->deskripsi,
                'jenis'          => $i->jenis,
                'file'           => $i->file ? asset('storage/' . $i->file) : null,
                'url'            => $i->url,
                'tahun'          => $i->tahun,
                'published_at'   => $i->published_at?->format('Y-m-d'),
                'jenis_dokumen'  => $i->jenisDokumen?->jenis_dokumen,
            ]);

        return response()->json($items);
    }
}
