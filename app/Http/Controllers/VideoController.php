<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    // Menampilkan daftar video
    public function index()
    {
        $videos = Video::latest()->get();
        return view('video.index', compact('videos'));
    }

    // Menampilkan form tambah video
    public function create()
    {
        return view('video.create');
    }

    // Menyimpan data video baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'judul_video' => 'required|string|max:255',
            'url_video' => 'required|url',
        ], [
            'judul_video.required' => 'Judul video wajib diisi.',
            'url_video.required' => 'URL video wajib diisi.',
            'url_video.url' => 'Format URL tidak valid (harus diawali http:// atau https://).'
        ]);

        Video::create([
            'judul_video' => $request->judul_video,
            'url_video' => $request->url_video,
        ]);

        return redirect()->route('video.index')->with('success', 'Video berhasil ditambahkan.');
    }

    // Menampilkan form edit video
    public function edit(Video $video)
    {
        return view('video.edit', compact('video'));
    }

    // Mengupdate data video di database
    public function update(Request $request, Video $video)
    {
        $request->validate([
            'judul_video' => 'required|string|max:255',
            'url_video' => 'required|url',
        ]);

        $video->update([
            'judul_video' => $request->judul_video,
            'url_video' => $request->url_video,
        ]);

        return redirect()->route('video.index')->with('success', 'Video berhasil diperbarui.');
    }

    // Menghapus data video dari database
    public function destroy(Video $video)
    {
        // Operator tidak dapat menghapus data
        if (!auth()->user()->canDelete()) {
            return redirect()->route('video.index')
                ->with('error', 'Aksi ditolak. Hanya Admin atau Super Admin yang dapat menghapus video.');
        }

        $video->delete();
        return redirect()->route('video.index')->with('success', 'Video berhasil dihapus.');
    }
}