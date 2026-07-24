@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')
@section('page-subtitle', 'Manajemen akun pengguna sistem')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800">Daftar Pengguna</h3>
        <p class="text-sm text-gray-400 mt-0.5">Total {{ $users->total() }} pengguna terdaftar</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn-primary" id="addUserBtn">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        Tambah Pengguna
    </a>
</div>

<div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">
    <div class="overflow-x-auto">
        <table class="w-full data-table">
            <thead>
                <tr>
                    <th class="text-left w-8">#</th>
                    <th class="text-left">Pengguna</th>
                    <th class="text-left">Email</th>
                    <th class="text-left">Role</th>
                    <th class="text-left">Bergabung</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td class="text-gray-400 text-xs">{{ $users->firstItem() + $index }}</td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold text-white flex-shrink-0" style="background: linear-gradient(135deg, #148F9A, #0d7a84);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $user->name }}</p>
                                @if($user->id === Auth::id())
                                    <span class="text-xs bg-teal-50 text-teal-600 px-2 py-0.5 rounded-full font-medium">Anda</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-sm text-gray-600">{{ $user->email }}</td>
                    <td>
                        @php
                            $roleStyle = match($user->role) {
                                'super_admin' => 'background:#fef3c7; color:#92400e;',
                                'admin'       => 'background:#ede9fe; color:#5b21b6;',
                                default       => 'background:#f0fdf4; color:#166534;',
                            };
                        @endphp
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="{{ $roleStyle }}">
                            {{ $user->roleName() }}
                        </span>
                    </td>
                    <td class="text-sm text-gray-500 whitespace-nowrap">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="btn-edit" id="editUser{{ $user->id }}">Edit</a>
                            @if($user->id !== Auth::id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus pengguna {{ addslashes($user->name) }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" id="deleteUser{{ $user->id }}">Hapus</button>
                            </form>
                            @else
                            <span class="text-xs text-gray-300 px-3 py-1.5 rounded-md border border-dashed border-gray-200">Tidak dapat dihapus</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-16 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-500">Belum ada pengguna</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection

