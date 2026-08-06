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
    {{-- Admin design system — compiled & cached by Vite --}}
    @vite('resources/css/admin.css')
    @stack('styles')
</head>
<body class="bg-gray-50 h-screen overflow-hidden">

<div class="flex h-screen">

    {{-- ======= SIDEBAR ======= --}}
    @include('partials.sidebar')
    {{-- ======= END SIDEBAR ======= --}}

    {{-- ======= MAIN CONTENT ======= --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between flex-shrink-0" style="box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
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

        {{-- Scrollable area: Flash + Main + Footer --}}
        <div class="flex-1 overflow-y-auto flex flex-col">

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
        {{-- ======= END Scrollable area ======= --}}

    </div>
    {{-- ======= END MAIN CONTENT ======= --}}

</div>

{{-- Sidebar dropdown — compiled & cached by Vite --}}
@vite('resources/js/sidebar.js')

@stack('scripts')
</body>
</html>