<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIMBA - Panel Admin Sistem Informasi Manajemen Bakorwil">
    <title>@yield('title', 'Dashboard') — SIMBA Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(255,255,255,0.7);
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        .sidebar-link.active {
            background: rgba(255,255,255,0.18);
            color: #fff;
        }

        /* Alert flash messages */
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* Table */
        .data-table th {
            background: #f8fafc;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 0.875rem 1rem;
        }
        .data-table td {
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        .data-table tr:hover td {
            background: #f8fafc;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #148F9A, #0d7a84);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0d7a84, #0a6870);
            box-shadow: 0 4px 15px rgba(20,143,154,0.3);
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: white;
            color: #374151;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }
        .btn-danger {
            background: #fef2f2;
            color: #dc2626;
            padding: 0.375rem 0.875rem;
            border-radius: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-danger:hover {
            background: #fee2e2;
        }
        .btn-edit {
            background: #eff6ff;
            color: #2563eb;
            padding: 0.375rem 0.875rem;
            border-radius: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-edit:hover {
            background: #dbeafe;
        }

        /* Form inputs */
        .form-input {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: #374151;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #148F9A;
            box-shadow: 0 0 0 3px rgba(20,143,154,0.12);
        }
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.375rem;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">

<div class="flex min-h-screen">

    {{-- ======= SIDEBAR ======= --}}
    <aside class="w-64 flex-shrink-0 flex flex-col" style="background: linear-gradient(180deg, #148F9A 0%, #0d7a84 50%, #0a6870 100%);">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none">
                    <path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4M9 9h1m4 0h1M9 13h1m4 0h1" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-bold text-lg leading-none">SIMBA</p>
                <p class="text-white/60 text-xs mt-0.5">Admin Panel</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-5 space-y-1">
            <p class="text-white/40 text-xs font-semibold uppercase tracking-widest px-1 mb-3">Menu Utama</p>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('berita.index') }}"
               class="sidebar-link {{ Request::routeIs('berita.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                Kelola Berita
            </a>

            {{-- MENU BARU: Kelola Video --}}
            <a href="{{ route('video.index') }}"
               class="sidebar-link {{ Request::routeIs('video.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Kelola Video
            </a>

            @if(!Auth::user()->isOperator())
            <a href="{{ route('riwayat.index') }}"
               class="sidebar-link {{ Request::routeIs('riwayat.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Riwayat Aktivitas
            </a>
            @endif

            @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('users.index') }}"
               class="sidebar-link {{ Request::routeIs('users.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Kelola Pengguna
            </a>
            @endif
        </nav>

        {{-- User Info at Bottom --}}
        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-2 py-2 rounded-xl bg-white/10">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                    <p class="text-white/50 text-xs truncate">{{ Auth::user()->email }}</p>
                    @php
                        $roleColor = match(Auth::user()->role) {
                            'super_admin' => 'background: rgba(251,191,36,0.25); color: #fde68a;',
                            'admin'       => 'background: rgba(99,102,241,0.25); color: #c7d2fe;',
                            default       => 'background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.6);',
                        };
                    @endphp
                    <span class="inline-block mt-1 text-xs font-semibold px-2 py-0.5 rounded-full" style="{{ $roleColor }}">{{ Auth::user()->roleName() }}</span>
                </div>
            </div>
        </div>
    </aside>
    {{-- ======= END SIDEBAR ======= --}}

    {{-- ======= MAIN CONTENT ======= --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-10" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div>
                <h2 class="text-gray-800 font-semibold text-base">@yield('page-title', 'Dashboard')</h2>
                <p class="text-gray-400 text-xs mt-0.5">@yield('page-subtitle', 'Panel Administrasi SIMBA')</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">Halo, <strong class="text-gray-700">{{ Auth::user()->name }}</strong></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" id="logoutButton"
                        class="flex items-center gap-2 text-sm text-gray-500 hover:text-red-600 bg-gray-50 hover:bg-red-50 px-3 py-2 rounded-lg border border-gray-200 hover:border-red-200 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="alert-success rounded-lg px-4 py-3 flex items-center gap-3 mb-0" id="flashAlert">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-error rounded-lg px-4 py-3 flex items-center gap-3 mb-0" id="flashAlert">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-gray-100 px-6 py-3 text-center">
            <p class="text-xs text-gray-400">© 2026 SIMBA (Sistem Informasi Manajemen Bakorwil). All rights reserved.</p>
        </footer>

    </div>
    {{-- ======= END MAIN CONTENT ======= --}}

</div>

@stack('scripts')
</body>
</html>