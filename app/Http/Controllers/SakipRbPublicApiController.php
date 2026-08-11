<?php

namespace App\Http\Controllers;

use App\Models\SakipRb;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * SakipRbPublicApiController
 *
 * Menyediakan endpoint JSON read-only untuk dokumen SAKIP-RB
 * yang ditampilkan di halaman publik frontend.
 */
class SakipRbPublicApiController extends Controller
{
    /**
     * GET /api/sakip-rb
     *
     * Mengembalikan daftar dokumen SAKIP-RB yang dipublish.
     * Query params:
     *   - tahun  (int, opsional — filter by tahun)
     *
     * Response shape:
     * {
     *   "tahun_tersedia": [2025, 2024, ...],
     *   "tahun_aktif": 2025,
     *   "data": [
     *     {
     *       "id": 1,
     *       "jenis_dokumen": "...",
     *       "klasifikasi": "...",
     *       "tahun": 2025,
     *       "jenis": "dokumen" | "link",
     *       "file_url": "http://..." | null,
     *       "url": "http://..." | null
     *     },
     *     ...
     *   ]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $tahunList  = SakipRb::tahunTersedia();
        $tahunAktif = (int) $request->query('tahun', $tahunList[0] ?? now()->year);

        $items = SakipRb::where('tahun', $tahunAktif)
            ->where('status', '1')
            ->orderBy('id')
            ->get()
            ->map(fn ($s) => [
                'id'            => $s->id,
                'jenis_dokumen' => $s->jenis_dokumen,
                'klasifikasi'   => $s->klasifikasi,
                'tahun'         => $s->tahun,
                'jenis'         => $s->file ? 'dokumen' : ($s->url ? 'link' : null),
                'file_url'      => $s->file ? Storage::disk('public')->url($s->file) : null,
                'url'           => $s->url,
            ]);

        return response()->json([
            'tahun_tersedia' => $tahunList,
            'tahun_aktif'    => $tahunAktif,
            'data'           => $items,
        ]);
    }
}
