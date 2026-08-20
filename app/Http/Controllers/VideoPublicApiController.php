<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\JsonResponse;

/**
 * VideoPublicApiController
 *
 * Menyediakan endpoint JSON read-only (tanpa autentikasi) untuk
 * daftar video yang ditampilkan di sidebar frontend publik (S1MB4-Frontend).
 */
class VideoPublicApiController extends Controller
{
    /**
     * GET /api/videos
     *
     * Mengembalikan semua video, diurutkan terbaru.
     * Thumbnail YouTube digenerate otomatis dari url_video.
     *
     * Response: array of {
     *   id, judul_video, url_video, thumbnail_url
     * }
     */
    public function index(): JsonResponse
    {
        $videos = Video::latest()
            ->get()
            ->map(fn ($v) => [
                'id'            => $v->id,
                'judul_video'   => $v->judul_video,
                'url_video'     => $v->url_video,
                'thumbnail_url' => $this->extractYoutubeThumbnail($v->url_video),
            ]);

        return response()->json($videos);
    }

    // -------------------------------------------------------------------------
    // Private helper
    // -------------------------------------------------------------------------

    /**
     * Ekstrak thumbnail YouTube dari berbagai format URL yang umum:
     *   - https://www.youtube.com/watch?v=VIDEO_ID
     *   - https://youtu.be/VIDEO_ID
     *   - https://www.youtube.com/embed/VIDEO_ID
     *   - https://youtube.com/shorts/VIDEO_ID
     *
     * Mengembalikan URL thumbnail mqdefault (320×180) atau null jika bukan YouTube.
     */
    private function extractYoutubeThumbnail(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $videoId = null;

        // Format: youtu.be/VIDEO_ID
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            $videoId = $m[1];
        }
        // Format: youtube.com/watch?v=VIDEO_ID
        elseif (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $m)) {
            $videoId = $m[1];
        }
        // Format: youtube.com/embed/VIDEO_ID atau youtube.com/shorts/VIDEO_ID
        elseif (preg_match('/(?:embed|shorts)\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            $videoId = $m[1];
        }

        if ($videoId) {
            return "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg";
        }

        return null;
    }
}
