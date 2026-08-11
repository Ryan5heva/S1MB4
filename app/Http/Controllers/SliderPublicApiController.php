<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

/**
 * SliderPublicApiController
 *
 * Menyediakan endpoint JSON read-only untuk slider hero
 * yang ditampilkan di halaman beranda publik.
 */
class SliderPublicApiController extends Controller
{
    /**
     * GET /api/sliders
     *
     * Mengembalikan semua slider aktif, diurutkan sesuai kolom urutan.
     *
     * Response shape:
     * [
     *   {
     *     "id": 1,
     *     "image_url": "http://...",
     *     "url_tujuan": "http://..." | null,
     *     "alt": "Slide 1"
     *   },
     *   ...
     * ]
     */
    public function index(): JsonResponse
    {
        $sliders = Slider::tampil()
            ->get()
            ->map(fn ($s) => [
                'id'         => $s->id,
                'image_url'  => Storage::disk('public')->url($s->gambar),
                'url_tujuan' => $s->url_tujuan,
                'alt'        => 'Slide ' . $s->id,
            ]);

        return response()->json($sliders);
    }
}
