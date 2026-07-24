<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    /**
     * Tampilkan daftar berita.
     */
    public function index()
    {
        $berita = Berita::with('user')->latest()->paginate(10);
        return view('berita.index', compact('berita'));
    }

    /**
     * Tampilkan form tambah berita.
     */
    public function create()
    {
        return view('berita.create');
    }

    /**
     * Simpan berita baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'  => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $slug = $this->generateUniqueSlug($validated['judul']);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('berita', 'public');
        }

        Berita::create([
            'judul'   => $validated['judul'],
            'slug'    => $slug,
            'konten'  => $validated['konten'],
            'gambar'  => $gambarPath,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Show single berita (redirect to index).
     */
    public function show(Berita $beritum)
    {
        return redirect()->route('berita.index');
    }

    /**
     * Tampilkan form edit berita.
     */
    public function edit(Berita $beritum)
    {
        return view('berita.edit', ['berita' => $beritum]);
    }

    /**
     * Update data berita di database.
     */
    public function update(Request $request, Berita $beritum)
    {
        $validated = $request->validate([
            'judul'  => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        // Regenerate slug only if judul changed
        $slug = $beritum->slug;
        if ($beritum->judul !== $validated['judul']) {
            $slug = $this->generateUniqueSlug($validated['judul'], $beritum->id);
        }

        $gambarPath = $beritum->gambar;
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($beritum->gambar && Storage::disk('public')->exists($beritum->gambar)) {
                Storage::disk('public')->delete($beritum->gambar);
            }
            $gambarPath = $request->file('gambar')->store('berita', 'public');
        }

        $beritum->update([
            'judul'  => $validated['judul'],
            'slug'   => $slug,
            'konten' => $validated['konten'],
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Hapus berita.
     */
    public function destroy(Berita $beritum)
    {
        // Operator tidak dapat menghapus data
        if (!auth()->user()->canDelete()) {
            return redirect()->route('berita.index')
                ->with('error', 'Aksi ditolak. Hanya Admin atau Super Admin yang dapat menghapus berita.');
        }

        if ($beritum->gambar && Storage::disk('public')->exists($beritum->gambar)) {
            Storage::disk('public')->delete($beritum->gambar);
        }

        $beritum->delete();

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Generate slug unik dari judul.
     */
    private function generateUniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $slug = Str::slug($judul);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Berita::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
