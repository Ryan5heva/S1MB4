<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIMBA - Sistem Informasi Manajemen Bakorwil. Login untuk mengakses panel administrasi.">
    <title>Login — SIMBA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-gradient-radial {
            background: radial-gradient(ellipse at center, #f8f6f1 0%, #f0ece3 40%, #e8e2d7 100%);
        }
        .input-focus:focus {
            outline: none;
            border-color: #148F9A;
            box-shadow: 0 0 0 3px rgba(20, 143, 154, 0.15);
        }
        .btn-teal {
            background: linear-gradient(135deg, #148F9A 0%, #0d7a84 100%);
            transition: all 0.2s ease;
        }
        .btn-teal:hover {
            background: linear-gradient(135deg, #0d7a84 0%, #0a6870 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(20, 143, 154, 0.35);
        }
        .btn-teal:active {
            transform: translateY(0);
        }
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 20px 60px -10px rgba(0,0,0,0.12);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/login-bg.jpg') }}');">

    {{-- Main Content --}}
    <main class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">

            {{-- Card --}}
            <div class="bg-white rounded-2xl card-shadow px-8 py-10">

                {{-- Header --}}
                <div class="flex flex-col items-center mb-8">
                    <div class="flex items-center gap-3 mb-3">
                        {{-- Building Icon --}}
                        <div class="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none">
                                <path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4M9 9h1m4 0h1M9 13h1m4 0h1" stroke="#148F9A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <rect x="10" y="3" width="4" height="4" rx="0.5" stroke="#148F9A" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold tracking-wide" style="color: #148F9A;">SIMBA</h1>
                    </div>
                    <p class="text-sm text-gray-500 text-center">Sistem Informasi Manajemen Bakorwil</p>
                </div>

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="mb-5 bg-red-50 border border-red-200 rounded-lg px-4 py-3 flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-600">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan Email"
                            autocomplete="email"
                            required
                            class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 transition-all duration-200"
                        >
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan Password"
                                autocomplete="current-password"
                                required
                                class="input-focus w-full px-4 py-3 border border-gray-300 rounded-lg text-sm text-gray-800 placeholder-gray-400 transition-all duration-200 pr-12"
                            >
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="w-4 h-4 rounded border-gray-300 cursor-pointer"
                            style="accent-color: #148F9A;"
                        >
                        <label for="remember" class="ml-2 text-sm text-gray-500 cursor-pointer select-none">
                            Remember Me
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        id="loginButton"
                        class="btn-teal w-full py-3 px-4 rounded-lg text-white text-sm font-semibold tracking-wide mt-2"
                    >
                        Masuk Sekarang
                    </button>
                </form>

            </div>
            {{-- End Card --}}

        </div>
    </main>

    {{-- Footer --}}
    <footer class="py-4 text-center">
        <p class="text-xs text-gray-400">© 2026 SIMBA (Sistem Informasi Manajemen Bakorwil)</p>
    </footer>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        });
    </script>
</body>
</html>
