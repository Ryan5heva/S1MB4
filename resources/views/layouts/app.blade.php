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
    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ============================================
           SIDEBAR — Base Link
        ============================================ */
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
            cursor: pointer;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        .sidebar-link.active {
            background: rgba(255,255,255,0.18);
            color: #fff;
        }

        /* ============================================
           SIDEBAR — Dropdown "Kelola"
        ============================================ */

        /* Tombol trigger dropdown */
        .sidebar-group-trigger {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(255,255,255,0.7);
            transition: all 0.2s ease;
            cursor: pointer;
            width: 100%;
            background: none;
            border: none;
            text-align: left;
        }
        .sidebar-group-trigger:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        /* State aktif (salah satu submenu sedang aktif) */
        .sidebar-group-trigger.group-active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        /* Ikon panah — rotate saat terbuka */
        .sidebar-arrow {
            margin-left: auto;
            font-size: 0.75rem;
            transition: transform 0.25s ease;
            flex-shrink: 0;
        }
        .sidebar-group-trigger[aria-expanded="true"] .sidebar-arrow {
            transform: rotate(90deg);
        }

        /* Container submenu dengan animasi collapse */
        .sidebar-submenu {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease, opacity 0.25s ease;
            opacity: 0;
        }
        .sidebar-submenu.submenu-open {
            max-height: 400px;
            opacity: 1;
        }

        /* Item submenu */
        .sidebar-submenu-inner {
            margin-top: 0.25rem;
            padding-left: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }
        .sidebar-sublink {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0.875rem;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: rgba(255,255,255,0.6);
            transition: all 0.2s ease;
            text-decoration: none;
            position: relative;
        }
        .sidebar-sublink::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: rgba(255,255,255,0.5);
            border-radius: 2px;
            transition: height 0.2s ease;
        }
        .sidebar-sublink:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .sidebar-sublink:hover::before {
            height: 60%;
        }
        .sidebar-sublink.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-weight: 600;
        }
        .sidebar-sublink.active::before {
            height: 70%;
            background: #fff;
        }

        /* ============================================
           SIDEBAR — Logout Button
        ============================================ */
        .sidebar-logout-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(255,255,255,0.6);
            transition: all 0.2s ease;
            cursor: pointer;
            width: 100%;
            background: none;
            border: none;
            text-align: left;
        }
        .sidebar-logout-btn:hover {
            background: rgba(239,68,68,0.2);
            color: #fca5a5;
        }

        /* ============================================
           SIDEBAR — Divider
        ============================================ */
        .sidebar-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 0.5rem 0;
        }

        /* ============================================
           ALERT — Flash Messages
        ============================================ */
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

        /* ============================================
           TABLE
        ============================================ */
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

        /* ============================================
           BUTTONS
        ============================================ */
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
        .btn-preview {
            background: #f0fdfa;
            color: #0d9488;
            padding: 0.375rem 0.875rem;
            border-radius: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid #ccfbf1;
        }
        .btn-preview:hover {
            background: #ccfbf1;
            color: #0f766e;
        }

        /* ============================================
           FORM INPUTS
        ============================================ */
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

        {{-- ===================================
             NAVIGASI SIDEBAR
        =================================== --}}
        @php
            /*
             * Tentukan apakah salah satu submenu "Kelola" sedang aktif.
             * Digunakan untuk membuka dropdown secara otomatis.
             */
            $isKelolaDanBerita  = request()->routeIs('berita.*');
            $isKelolaVideo      = request()->routeIs('video.*');
            $isKelolaUsers      = request()->routeIs('users.*');
            $isKelolaRiwayat    = request()->routeIs('riwayat.*');
            // Pengguna berdiri sendiri, tidak termasuk dalam dropdown Kelola
            $isKelolaActive     = $isKelolaDanBerita || $isKelolaVideo || $isKelolaRiwayat;

            // PPID — tambah variabel baru setiap menu PPID diimplementasikan
            $isPpidBerkala      = request()->routeIs('ppid.berkala.*');
            $isPpidSertaMerta   = request()->routeIs('ppid.serta_merta.*');
            $isPpidActive       = $isPpidBerkala || $isPpidSertaMerta;
        @endphp

        <nav class="flex-1 px-4 py-5 flex flex-col gap-1">
            <p class="text-white/40 text-xs font-semibold uppercase tracking-widest px-1 mb-2">Menu Utama</p>

            {{-- ─── Dashboard ─── --}}
            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house" style="font-size: 1rem;"></i>
                <span>Dashboard</span>
            </a>

            <hr class="sidebar-divider">
            <p class="text-white/40 text-xs font-semibold uppercase tracking-widest px-1 mb-1 mt-1">Manajemen</p>

            {{-- ─── Dropdown: Kelola ─── --}}
            <div>
                {{-- Trigger --}}
                <button
                    type="button"
                    id="sidebarKelolaTrigger"
                    aria-expanded="{{ $isKelolaActive ? 'true' : 'false' }}"
                    aria-controls="sidebarKelolaMenu"
                    onclick="toggleSidebarGroup(this)"
                    class="sidebar-group-trigger {{ $isKelolaActive ? 'group-active' : '' }}"
                >
                    <i class="bi bi-folder" style="font-size: 1rem;"></i>
                    <span>Kelola</span>
                    <i class="bi bi-chevron-right sidebar-arrow"></i>
                </button>

                {{-- Submenu --}}
                <div
                    id="sidebarKelolaMenu"
                    class="sidebar-submenu {{ $isKelolaActive ? 'submenu-open' : '' }}"
                    role="region"
                    aria-labelledby="sidebarKelolaTrigger"
                >
                    <div class="sidebar-submenu-inner">

                        {{-- Berita (semua role) --}}
                        <a href="{{ route('berita.index') }}"
                           class="sidebar-sublink {{ $isKelolaDanBerita ? 'active' : '' }}">
                            <i class="bi bi-newspaper"></i>
                            <span>Berita</span>
                        </a>

                        {{-- Video (semua role) --}}
                        <a href="{{ route('video.index') }}"
                           class="sidebar-sublink {{ $isKelolaVideo ? 'active' : '' }}">
                            <i class="bi bi-camera-video"></i>
                            <span>Video</span>
                        </a>

                        {{-- Riwayat Aktivitas (non-operator) --}}
                        @if(!Auth::user()->isOperator())
                        <a href="{{ route('riwayat.index') }}"
                           class="sidebar-sublink {{ $isKelolaRiwayat ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i>
                            <span>Riwayat Aktivitas</span>
                        </a>
                        @endif

                    </div>
                </div>
            </div>

            {{-- ─── Pengguna (standalone, super admin saja) ─── --}}
            @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('users.index') }}"
               class="sidebar-link {{ $isKelolaUsers ? 'active' : '' }}">
                <i class="bi bi-people" style="font-size: 1rem;"></i>
                <span>Pengguna</span>
            </a>
            @endif

            <hr class="sidebar-divider">
            <p class="text-white/40 text-xs font-semibold uppercase tracking-widest px-1 mb-1 mt-1">PPID</p>

            {{-- ─── Dropdown: PPID ─── --}}
            <div>
                {{-- Trigger --}}
                <button
                    type="button"
                    id="sidebarPpidTrigger"
                    aria-expanded="{{ $isPpidActive ? 'true' : 'false' }}"
                    aria-controls="sidebarPpidMenu"
                    onclick="toggleSidebarGroup(this)"
                    class="sidebar-group-trigger {{ $isPpidActive ? 'group-active' : '' }}"
                >
                    <i class="bi bi-shield-check" style="font-size: 1rem;"></i>
                    <span>PPID</span>
                    <i class="bi bi-chevron-right sidebar-arrow"></i>
                </button>

                {{-- Submenu PPID --}}
                <div
                    id="sidebarPpidMenu"
                    class="sidebar-submenu {{ $isPpidActive ? 'submenu-open' : '' }}"
                    role="region"
                    aria-labelledby="sidebarPpidTrigger"
                >
                    <div class="sidebar-submenu-inner">

                        {{-- Informasi Berkala (aktif) --}}
                        <a href="{{ route('ppid.berkala.index') }}"
                           class="sidebar-sublink {{ $isPpidBerkala ? 'active' : '' }}">
                            <i class="bi bi-calendar-check"></i>
                            <span>Informasi Berkala</span>
                        </a>

                        {{-- Informasi Serta Merta --}}
                        <a href="{{ route('ppid.serta_merta.index') }}"
                           class="sidebar-sublink {{ $isPpidSertaMerta ? 'active' : '' }}">
                            <i class="bi bi-lightning-charge"></i>
                            <span>Serta Merta</span>
                        </a>

                        {{-- Informasi Setiap Saat (coming soon) --}}
                        <span class="sidebar-sublink" style="opacity:0.35; cursor:not-allowed;" title="Segera hadir">
                            <i class="bi bi-clock"></i>
                            <span>Setiap Saat</span>
                            <span style="margin-left:auto; font-size:0.6rem; background:rgba(255,255,255,0.15); padding:0.1rem 0.4rem; border-radius:9999px;">Soon</span>
                        </span>

                        {{-- Informasi Dikecualikan (coming soon) --}}
                        <span class="sidebar-sublink" style="opacity:0.35; cursor:not-allowed;" title="Segera hadir">
                            <i class="bi bi-lock"></i>
                            <span>Dikecualikan</span>
                            <span style="margin-left:auto; font-size:0.6rem; background:rgba(255,255,255,0.15); padding:0.1rem 0.4rem; border-radius:9999px;">Soon</span>
                        </span>

                    </div>
                </div>
            </div>

        </nav>

        {{-- ─── User Info ─── --}}
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

{{-- ============================================
     SIDEBAR DROPDOWN — Vanilla JS (no jQuery)
============================================ --}}
<script>
    /**
     * Toggle expand/collapse grup sidebar.
     * Dipanggil via onclick pada tombol trigger.
     *
     * @param {HTMLElement} trigger - Elemen <button> yang diklik
     */
    function toggleSidebarGroup(trigger) {
        const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
        const menuId     = trigger.getAttribute('aria-controls');
        const menu       = document.getElementById(menuId);

        if (!menu) return;

        if (isExpanded) {
            // ── Tutup ──
            trigger.setAttribute('aria-expanded', 'false');
            trigger.classList.remove('group-active');
            menu.classList.remove('submenu-open');
        } else {
            // ── Buka ──
            trigger.setAttribute('aria-expanded', 'true');
            trigger.classList.add('group-active');
            menu.classList.add('submenu-open');
        }
    }
</script>

@stack('scripts')
</body>
</html>