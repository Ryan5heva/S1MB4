<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use App\Models\PpidInformasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * SakipRbPublicApiController
 *
 * Menyediakan endpoint JSON read-only untuk dokumen SAKIP-RB
 * yang ditampilkan di halaman publik frontend.
 *
 * FIX (2026-08-12): Sebelumnya controller ini membaca dari tabel `sakip_rbs`
 * (model SakipRb) yang berbeda dan sudah tidak digunakan sebagai sumber data utama.
 * Admin menginput data SAKIP-RB melalui /ppid?jenis_dokumen_id=14, yang menyimpan
 * data ke tabel `ppid_informasi` dengan id_jenis_dokumen = 14 (klasifikasi = 'sakip').
 * Controller ini sekarang membaca dari sumber yang sama agar konsisten.
 */
class SakipRbPublicApiController extends Controller
{
    /**
     * GET /api/sakip-rb
     *
     * Mengembalikan daftar dokumen SAKIP-RB yang dipublish.
     * Data diambil dari tabel ppid_informasi, difilter berdasarkan
     * id_jenis_dokumen yang berklasifikasi 'sakip' di tabel jenis_dokumen.
     *
     * Query params:
     *   - tahun  (int, opsional — filter by tahun)
     *
     * Response shape:
     * {
     *   "tahun_tersedia": [2026, 2025, ...],
     *   "tahun_aktif": 2026,
     *   "data": [
     *     {
     *       "id": 1,
     *       "jenis_dokumen": "...",
     *       "klasifikasi": "SAKIP",
     *       "tahun": 2026,
     *       "jenis": "dokumen" | "link" | null,
     *       "file_url": "http://..." | null,
     *       "url": "http://..." | null
     *     },
     *     ...
     *   ]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        // Ambil semua id jenis_dokumen yang berklasifikasi 'sakip'
        // (menggunakan klasifikasi case-insensitive agar robust)
        $sakipIds = JenisDokumen::whereRaw("LOWER(klasifikasi) = 'sakip'")
            ->pluck('id');

        // Daftar tahun yang tersedia (hanya dari data yang dipublish)
        $tahunList = PpidInformasi::whereIn('id_jenis_dokumen', $sakipIds)
            ->where('status', 'publish')
            ->whereNotNull('tahun')
            ->orderByDesc('tahun')
            ->distinct()
            ->pluck('tahun')
            ->map(fn ($t) => (int) $t)
            ->toArray();

        // Fallback jika belum ada data publish
        if (empty($tahunList)) {
            $tahunList = [now()->year];
        }

        // Tahun aktif: dari query param atau default ke tahun terbaru
        $tahunAktif = (int) $request->query('tahun', $tahunList[0]);

        // Ambil item untuk tahun aktif, hanya yang publish
        $items = PpidInformasi::with('jenisDokumen')
            ->whereIn('id_jenis_dokumen', $sakipIds)
            ->where('status', 'publish')
            ->where('tahun', $tahunAktif)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get()
            ->map(fn ($item) => [
                'id'            => $item->id,
                'jenis_dokumen' => $item->nama_informasi,
                'klasifikasi'   => $item->jenisDokumen?->jenis_dokumen,
                'tahun'         => (int) $item->tahun,
                'jenis'         => $item->jenis,
                'file_url'      => $item->file_url,
                'url'           => $item->url,
            ]);

        return response()->json([
            'tahun_tersedia' => $tahunList,
            'tahun_aktif'    => $tahunAktif,
            'data'           => $items,
        ]);
    }
}
