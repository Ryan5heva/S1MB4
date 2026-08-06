{{--
    partials/sidebar/menu.blade.php
    ──────────────────────────────
    Navigasi utama sidebar: semua link dan dropdown grup.
    Variabel aktif-route ($isKelolaActive, $isDokumenActive, dll.) didefinisikan
    di partials/sidebar.blade.php dan tersedia di sini via Blade include scope.
    Di-include oleh: partials/sidebar.blade.php
--}}

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

            </div>
        </div>
    </div>

    <hr class="sidebar-divider">
    <p class="text-white/40 text-xs font-semibold uppercase tracking-widest px-1 mb-1 mt-1">Dokumen</p>

    {{-- ─── Dropdown: Dokumen ─── --}}
    <div>
        {{-- Trigger --}}
        <button
            type="button"
            id="sidebarDokumenTrigger"
            aria-expanded="{{ $isDokumenActive ? 'true' : 'false' }}"
            aria-controls="sidebarDokumenMenu"
            onclick="toggleSidebarGroup(this)"
            class="sidebar-group-trigger {{ $isDokumenActive ? 'group-active' : '' }}"
        >
            <i class="bi bi-folder2-open" style="font-size: 1rem;"></i>
            <span>Dokumen</span>
            <i class="bi bi-chevron-right sidebar-arrow"></i>
        </button>

        {{-- Submenu --}}
        <div
            id="sidebarDokumenMenu"
            class="sidebar-submenu {{ $isDokumenActive ? 'submenu-open' : '' }}"
            role="region"
            aria-labelledby="sidebarDokumenTrigger"
        >
            <div class="sidebar-submenu-inner">

                {{-- PPID (kategori dipilih via dropdown di dalam halaman) --}}
                <a href="{{ route('ppid.berkala.index') }}"
                   class="sidebar-sublink {{ $isPpidGroup ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i>
                    <span>PPID</span>
                </a>

                {{-- Jenis Dokumen (master data, super admin saja) --}}
                @if(Auth::user()->isSuperAdmin())
                <a href="{{ route('jenis-dokumen.index') }}"
                   class="sidebar-sublink {{ $isJenisDokumen ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>
                    <span>Jenis Dokumen</span>
                </a>
                @endif

            </div>
        </div>
    </div>

    <hr class="sidebar-divider">
    <p class="text-white/40 text-xs font-semibold uppercase tracking-widest px-1 mb-1 mt-1">Tampilan</p>

    {{-- ─── Slider ─── --}}
    <a href="{{ route('slider.index') }}"
       class="sidebar-link {{ $isSliderActive ? 'active' : '' }}">
        <i class="bi bi-images" style="font-size: 1rem;"></i>
        <span>Slider</span>
    </a>

</nav>
