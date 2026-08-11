<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * BeritaPublicApiController
 *
 * Menyediakan endpoint JSON read-only (tanpa autentikasi) untuk
 * halaman berita publik pada frontend React (S1MB4-Frontend).
 */
class BeritaPublicApiController extends Controller
{
    /**
     * GET /api/berita
     *
     * Mengembalikan daftar berita, paginasi 12 item per halaman.
     * Query params:
     *   - per_page  (int, default 12, max 50)
     *   - page      (int, default 1)
     *   - breaking  (bool, jika ?breaking=1 hanya 5 berita terbaru tanpa paginasi)
     */
    public function index(Request $request): JsonResponse
    {
        // Mode breaking news (untuk ticker di Home)
        if ($request->boolean('breaking')) {
            $items = Berita::with('user')
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn ($b) => $this->mapSummary($b));

            return response()->json($items);
        }

        $perPage = min((int) $request->query('per_page', 12), 50);

        $paginator = Berita::with('user')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'data'         => collect($paginator->items())->map(fn ($b) => $this->mapSummary($b)),
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'total'        => $paginator->total(),
        ]);
    }

    /**
     * GET /api/berita/{id}
     *
     * Mengembalikan satu berita secara lengkap (dengan konten HTML penuh).
     */
    public function show(int $id): JsonResponse
    {
        $berita = Berita::with('user')->findOrFail($id);

        return response()->json($this->mapDetail($berita));
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /** Data ringkas untuk listing (tanpa konten penuh). */
    private function mapSummary(Berita $b): array
    {
        return [
            'id'         => $b->id,
            'judul'      => $b->judul,
            'ringkasan'  => Str::limit(strip_tags($b->konten), 160),
            'gambar_url' => $b->gambar ? Storage::disk('public')->url($b->gambar) : null,
            'penulis'    => $b->user?->name,
            'created_at' => $b->created_at?->toIso8601String(),
        ];
    }

    /** Data lengkap untuk halaman detail. */
    private function mapDetail(Berita $b): array
    {
        return [
            'id'         => $b->id,
            'judul'      => $b->judul,
            'konten'     => $b->konten,
            'gambar_url' => $b->gambar ? Storage::disk('public')->url($b->gambar) : null,
            'video_url'  => $b->video_url ?? null,
            'penulis'    => $b->user?->name,
            'created_at' => $b->created_at?->toIso8601String(),
            'updated_at' => $b->updated_at?->toIso8601String(),
        ];
    }
}
