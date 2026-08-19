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
     *
     * Kolom 'grup' di-derive otomatis dari nilai 'klasifikasi' melalui resolveGrup().
     * Admin tidak perlu mengisi kolom grup secara terpisah.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_dokumen' => 'required|string|max:255',
            'klasifikasi'   => 'required|string|max:255',
            'status'        => 'required|in:0,1',
        ]);

        $validated['grup'] = $this->resolveGrup(
            $validated['klasifikasi'],
            $validated['jenis_dokumen']
        );

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
     *
     * Kolom 'grup' di-derive ulang dari nilai 'klasifikasi' yang diperbarui.
     */
    public function update(Request $request, JenisDokumen $jenisDokumen)
    {
        $validated = $request->validate([
            'jenis_dokumen' => 'required|string|max:255',
            'klasifikasi'   => 'required|string|max:255',
            'status'        => 'required|in:0,1',
        ]);

        $validated['grup'] = $this->resolveGrup(
            $validated['klasifikasi'],
            $validated['jenis_dokumen']
        );

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

    /**
     * Petakan nilai klasifikasi dan/atau nama jenis dokumen ke salah satu dari 6 nilai grup resmi.
     *
     * Pencocokan bersifat case-insensitive dan berbasis keyword (substring match).
     * Kedua parameter — klasifikasi dan jenis_dokumen — digabung sebelum dicari,
     * sehingga jika salah satu mengandung keyword yang cocok, grup langsung ter-set
     * tanpa admin perlu memilih secara manual.
     *
     * Prioritas pencocokan (dari atas ke bawah, pertama cocok dipakai):
     *   berkala            → 'Informasi Berkala'
     *   serta merta        → 'Informasi Serta Merta'
     *   setiap saat        → 'Informasi Setiap Saat'
     *   dikecualikan       → 'Informasi Dikecualikan'
     *   laporan akses      → 'Laporan Akses Informasi'
     *   sakip / sakip-rb   → 'Lainnya'  (SAKIP punya halaman sendiri /sakip-rb, bukan optgroup PPID)
     *   semua lainnya      → 'Lainnya'
     *
     * @param  string  $klasifikasi   Nilai field 'klasifikasi' dari form
     * @param  string  $jenisDokumen  Nilai field 'jenis_dokumen' dari form (default kosong)
     */
    private function resolveGrup(string $klasifikasi, string $jenisDokumen = ''): string
    {
        // Gabungkan kedua nilai agar keyword bisa dideteksi dari field manapun.
        // Dipisah spasi agar tidak terjadi penggabungan kata yang tidak disengaja.
        $k = strtolower(trim($klasifikasi) . ' ' . trim($jenisDokumen));

        if (str_contains($k, 'berkala'))        return 'Informasi Berkala';
        if (str_contains($k, 'serta merta'))    return 'Informasi Serta Merta';
        if (str_contains($k, 'setiap saat'))    return 'Informasi Setiap Saat';
        if (str_contains($k, 'dikecualikan'))   return 'Informasi Dikecualikan';
        if (str_contains($k, 'laporan akses'))  return 'Laporan Akses Informasi';
        // 'sakip' tidak di-handle secara eksplisit — jatuh ke 'Lainnya' (behavior yang benar)

        return 'Lainnya';
    }
}