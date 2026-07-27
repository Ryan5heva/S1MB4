@extends('layouts.app')

@section('title', 'Riwayat Aktivitas')
@section('page-title', 'Riwayat Aktivitas')
@section('page-subtitle', 'Catatan aktivitas pengguna di sistem')

@section('content')
<div class="bg-white rounded-2xl overflow-hidden" style="box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06);">
    <div class="overflow-x-auto">
        <table class="w-full data-table">
            <thead>
                <tr>
                    <th class="text-left">No</th>
                    <th class="text-left">Tanggal & Waktu</th>
                    <th class="text-left">Pengguna</th>
                    <th class="text-left">Aktivitas</th>
                    <th class="text-left">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                <tr>
                    <td>{{ $logs->firstItem() + $index }}</td>
                    <td class="text-sm text-gray-500">{{ $log->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $log->user->name ?? 'Sistem' }} <span class="text-xs text-gray-400">({{ $log->user->roleName() ?? '-' }})</span></td>
                    <td>{{ $log->aktivitas }}</td>
                    <td class="text-sm text-gray-600">{{ $log->keterangan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-400">Belum ada riwayat aktivitas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection