{{--
    partials/sidebar/user-info.blade.php
    ─────────────────────────────────────
    Kotak info pengguna di bagian bawah sidebar:
    avatar inisial, nama, email, dan badge role.
    Di-include oleh: partials/sidebar.blade.php
--}}

<div class="px-4 py-4 border-t border-white/10">
    <div class="flex items-center gap-3 px-2 py-2 rounded-xl bg-white/10">

        {{-- Avatar inisial --}}
        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>

        {{-- Nama, email, dan badge role --}}
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
            <span class="inline-block mt-1 text-xs font-semibold px-2 py-0.5 rounded-full"
                  style="{{ $roleColor }}">{{ Auth::user()->roleName() }}</span>
        </div>

    </div>
</div>
