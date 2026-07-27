<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        if (auth()->user()->isOperator()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $logs = ActivityLog::with('user')->latest()->paginate(15);
        return view('riwayat.index', compact('logs'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat menghapus riwayat.');
        }

        $activityLog->delete();

        return redirect()->route('riwayat.index')
            ->with('success', 'Riwayat aktivitas berhasil dihapus.');
    }
}