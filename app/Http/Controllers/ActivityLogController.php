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
}