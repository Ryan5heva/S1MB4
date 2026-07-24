<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Tampilkan daftar pengguna.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Tampilkan form tambah pengguna.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Simpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', 'in:super_admin,admin,operator'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show single user (redirect to index).
     */
    public function show(User $user)
    {
        return redirect()->route('users.index');
    }

    /**
     * Tampilkan form edit pengguna.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update data pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role'     => ['required', 'in:super_admin,admin,operator'],
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Hapus pengguna.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
