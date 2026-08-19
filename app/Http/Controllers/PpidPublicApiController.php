<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use App\Models\PpidInformasi;
use App\Models\PpidKlasifikasi;
use Illuminate\Http\JsonResponse;

/**
 * PpidPublicApiController
 *
 * Menyediakan endpoint JSON read-only (tanpa autentikasi) untuk
 * halaman publik frontend (React / S1MB4-Frontend).
 *
 * Semua endpoint hanya mengembalikan data dengan status = 'publish'.
 */
class PpidPublicApiController extends Controller
{
    /**
     * GET /api/ppid/berkala
     *
     * Mengembalikan flat array dari semua item Informasi Berkala yang
     * dipublish, dilengkapi nama kategori (jenis_dokumen) untuk
     * pengelompokan tab di sisi frontend.
     *
     * Response shape:
     * [
     *   {
     *     "id": 1,
     *     "nama_informasi": "...",
     *     "deskripsi": "...",
     *     "jenis": "dokumen"|"link"|null,
     *     "file_url": "http://...|null",
     *     "url": "http://...|null",
     *     "urutan": 1,
     *     "tahun": "2024",
     *     "published_at": "2024-01-01",
     *     "jenis_dokumen": "Nama Kategori"
     *   },
     *   ...
     * ]
     */
    public function berkala(): JsonResponse
    {
        $items = PpidInformasi::berkala()
            ->published()
            ->with('jenisDokumen')
            ->orderBy('urutan')
            ->orderBy('id')
            ->get()
            ->map(fn ($item) => $this->mapItem($item, withKategori: true));

        return response()->json($items);
    }

    /**
     * GET /api/ppid/serta-merta
     *
     * Mengembalikan flat array dari semua item Informasi Serta Merta
     * yang dipublish.
     */
    public function sertaMerta(): JsonResponse
    {
        $items = PpidInformasi::sertaMerta()
            ->published()
            ->orderBy('urutan')
            ->get()
            ->map(fn ($item) => $this->mapItem($item));

        return response()->json($items);
    }

    /**
     * GET /api/ppid/setiap-saat
     *
     * Mengembalikan array section (dikelompokkan per kategori) dari
     * Informasi Setiap Saat yang dipublish.
     *
     * Response shape:
     * [
     *   { "kategori": "Nama Kategori", "items": [ {...}, ... ] },
     *   ...
     * ]
     */
    public function setiapSaat(): JsonResponse
    {
        $raw = PpidInformasi::setiapSaat()
            ->published()
            ->orderBy('kategori_urutan')
            ->orderBy('urutan')
            ->get();

        // Group by kategori, urutan sesuai KATEGORI_ORDER_SETIAP_SAAT.
        // ->toBase() mengonversi Eloquent Collection ke Support Collection agar
        // ->except(array_of_strings) bekerja dengan string key, bukan primary key.
        $grouped = $raw->toBase()->groupBy('kategori');

        $sections = collect(PpidInformasi::KATEGORI_ORDER_SETIAP_SAAT)
            ->filter(fn ($k) => $grouped->has($k))
            ->map(fn ($k) => [
                'kategori' => $k,
                'items'    => $grouped->get($k)->map(fn ($item) => $this->mapItem($item))->values(),
            ])
            ->values();

        // Tambahkan kategori yang tidak ada di konstanta (fallback)
        $extra = $grouped->except(PpidInformasi::KATEGORI_ORDER_SETIAP_SAAT)
            ->map(fn ($items, $k) => [
                'kategori' => $k,
                'items'    => $items->map(fn ($item) => $this->mapItem($item))->values(),
            ])
            ->values();

        return response()->json($sections->concat($extra));
    }

    /**
     * GET /api/ppid/dikecualikan
     *
     * Mengembalikan flat array dari semua item Informasi Dikecualikan
     * yang dipublish.
     */
    public function dikecualikan(): JsonResponse
    {
        $items = PpidInformasi::dikecualikan()
            ->published()
            ->orderBy('urutan')
            ->get()
            ->map(fn ($item) => $this->mapItem($item));

        return response()->json($items);
    }

    /**
     * GET /api/ppid/laporan-akses-informasi
     *
     * Mengembalikan flat array dari semua item Laporan Akses Informasi
     * yang dipublish.
     */
    public function laporanAksesInformasi(): JsonResponse
    {
        $items = PpidInformasi::laporanAksesInformasi()
            ->published()
            ->orderBy('urutan')
            ->get()
            ->map(fn ($item) => $this->mapItem($item));

        return response()->json($items);
    }

    /**
     * GET /api/ppid/dokumen/{slug}
     *
     * Mengembalikan daftar item Dokumen PPID yang dipublish
     * berdasarkan slug kategori.
     *
     * Slug → jenis_dokumen_id mapping:
     *   sk-ppid               → id = 11 (SK PPID)
     *   dip-bakorwil-1-madiun → id = 12 (Daftar Informasi Publik)
     *   llid-bakorwil-1-madiun → dikonfigurasi setelah entry DB tersedia
     *
     * Hanya mengembalikan item berstatus 'publish'.
     * Return 404 jika slug tidak dikenal atau belum dikonfigurasi.
     *
     * Response shape: flat array dari mapItem (tanpa field jenis_dokumen).
     */
    public function dokumen(string $slug): JsonResponse
    {
        // Peta slug → id_jenis_dokumen.
        // Nilai null berarti slug dikenal tapi belum dikonfigurasi → 404.
        // Tambahkan entri baru di sini setelah entry jenis_dokumen tersedia di DB.
        $slugMap = [
            'sk-ppid'                => 11,  // SK PPID
            'dip-bakorwil-1-madiun'  => 12,  // Daftar Informasi Publik
            // 'llid-bakorwil-1-madiun' => null,  // Tunggu ID dari admin panel
        ];

        if (! array_key_exists($slug, $slugMap) || $slugMap[$slug] === null) {
            return response()->json(['message' => 'Kategori dokumen tidak ditemukan.'], 404);
        }

        $items = PpidInformasi::where('id_jenis_dokumen', $slugMap[$slug])
            ->published()
            ->orderBy('urutan')
            ->orderBy('id')
            ->get()
            ->map(fn ($item) => $this->mapItem($item));

        return response()->json($items);
    }

    /**
     * GET /api/ppid/klasifikasi
     *
     * Mengembalikan daftar klasifikasi PPID yang aktif untuk
     * ditampilkan di dropdown navbar publik frontend.
     *
     * Response shape:
     * [
     *   { "label": "Informasi Berkala", "href": "/ppid/berkala", "urutan": 1 },
     *   ...
     * ]
     *
     * Diurutkan berdasarkan kolom `urutan` ascending.
     * Hanya mengembalikan klasifikasi dengan aktif = true.
     */
    public function klasifikasi(): JsonResponse
    {
        $items = PpidKlasifikasi::aktif()
            ->orderBy('urutan')
            ->get()
            ->map(fn ($k) => $k->toNavItem());

        return response()->json($items);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Petakan model ke array aman untuk dikembalikan sebagai JSON.
     *
     * @param  PpidInformasi  $item
     * @param  bool  $withKategori  Sertakan nama jenis_dokumen (untuk Berkala)
     */
    private function mapItem(PpidInformasi $item, bool $withKategori = false): array
    {
        $data = [
            'id'            => $item->id,
            'nama_informasi'=> $item->nama_informasi,
            'deskripsi'     => $item->deskripsi,
            'jenis'         => $item->jenis,
            'file_url'      => $item->file_url,
            'url'           => $item->url,
            'urutan'        => $item->urutan,
            'tahun'         => $item->tahun,
            'published_at'  => $item->published_at?->toDateString(),
        ];

        if ($withKategori) {
            $data['jenis_dokumen'] = $item->jenisDokumen?->jenis_dokumen;
        }

        return $data;
    }
}
