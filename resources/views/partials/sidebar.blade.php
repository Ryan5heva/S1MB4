{{--
    partials/sidebar.blade.php
    ──────────────────────────
    Sidebar utama aplikasi SIMBA.
    Mengandung: logo, navigasi menu, link Setting (admin+), dan info pengguna.
    Di-include oleh: layouts/app.blade.php
--}}

@php
    /*
     * Variabel aktif-route untuk seluruh sidebar.
     * Didefinisikan di sini agar tersedia baik di menu (via @include)
     * maupun di sidebar.blade.php itu sendiri (link Setting).
     */
    $isKelolaDanBerita  = request()->routeIs('berita.*');
    $isKelolaVideo      = request()->routeIs('video.*');
    $isKelolaActive     = $isKelolaDanBerita || $isKelolaVideo;

    $isPpidBerkala      = request()->routeIs('ppid.berkala.*');
    $isPpidSertaMerta   = request()->routeIs('ppid.serta_merta.*');
    $isPpidSetiapSaat   = request()->routeIs('ppid.setiap_saat.*');
    $isPpidDikecualikan = request()->routeIs('ppid.dikecualikan.*');
    $isPpidLaporanAkses = request()->routeIs('ppid.laporan_akses_informasi.*');
    $isPpidGroup        = $isPpidBerkala || $isPpidSertaMerta || $isPpidSetiapSaat || $isPpidDikecualikan || $isPpidLaporanAkses;

    $isJenisDokumen     = request()->routeIs('jenis-dokumen.*');
    $isDokumenActive    = $isPpidGroup || $isJenisDokumen;

    $isSliderActive     = request()->routeIs('slider.*');

    // Setting — aktif jika di halaman settings, riwayat, atau users
    $isSettingsActive   = request()->routeIs('settings.*')
                       || request()->routeIs('riwayat.*')
                       || request()->routeIs('users.*');
@endphp

<aside class="w-64 flex-shrink-0 flex flex-col h-screen overflow-y-auto"
       style="background: linear-gradient(180deg, #148F9A 0%, #0d7a84 50%, #0a6870 100%);">

    {{-- ─── Logo / Brand ─── --}}
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

    {{-- ─── Navigasi Menu ─── --}}
    @include('partials.sidebar.menu')

    {{-- ─── Setting (Admin & Super Admin saja) ─── --}}
    @if(!Auth::user()->isOperator())
    <div class="px-4 pb-2" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.5rem;">
        <a href="{{ route('settings.index') }}"
           class="sidebar-link {{ $isSettingsActive ? 'active' : '' }}">
            <i class="bi bi-gear" style="font-size: 1rem;"></i>
            <span>Setting</span>
        </a>
    </div>
    @endif

    {{-- ─── User Info ─── --}}
    @include('partials.sidebar.user-info')

</aside>
