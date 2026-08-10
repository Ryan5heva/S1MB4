{{--
    partials/tinymce.blade.php
    ──────────────────────────
    TinyMCE — Self-hosted (npm install tinymce, asset di-copy ke public/tinymce
    lewat viteStaticCopy saat `npm run build` / `npm run dev`).
    Tidak membutuhkan API key — bebas nag screen.

    Di-include oleh: berita/create.blade.php dan berita/edit.blade.php

    Penggunaan:
        @include('partials.tinymce', ['fieldId' => 'konten'])

    Parameter:
        $fieldId  — ID dari <textarea> target (default: 'konten')
--}}

@push('scripts')
{{-- Self-hosted TinyMCE dari public/tinymce (hasil copy dari node_modules/tinymce) --}}
<script src="{{ asset('tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
(function () {
    'use strict';

    const fieldId = {{ Js::from($fieldId ?? 'konten') }};

    tinymce.init({
        selector        : '#' + fieldId,
        base_url        : '/tinymce',   // path ke folder public/tinymce
        suffix          : '.min',       // pakai file *.min.js
        plugins         : 'lists',
        toolbar         : 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | outdent indent',
        menubar         : false,
        statusbar       : false,
        promotion       : false,
        branding        : false,
        license_key     : 'gpl',        // deklarasi lisensi GPL community — wajib di TinyMCE 7
        resize          : false,
        min_height      : 360,
        max_height      : 620,

        // ── Styling konten di dalam iframe editor ──────────────
        content_style   : [
            "body {",
            "    font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;",
            "    font-size: 15px;",
            "    line-height: 1.8;",
            "    color: #1f2937;",
            "    padding: 18px 22px;",
            "    margin: 0;",
            "}",
            "p { margin-bottom: 0.75rem; }"
        ].join('\n'),

        setup: function (editor) {
            // Sync editor → textarea setiap kali form di-submit
            editor.on('init', function () {
                const form = document.getElementById(fieldId)?.closest('form');
                if (form) {
                    form.addEventListener('submit', function () {
                        tinymce.triggerSave();   // tulis semua editor → textarea
                    });
                }
            });
        }
    });
})();
</script>

{{-- ─── Styling TinyMCE: clean minimal toolbar ─── --}}
<style>
    /* ── Wrapper utama ────────────────────────────────────────── */
    .tox.tox-tinymce {
        border        : 1px solid #d1d5db !important;
        border-radius : 0.625rem !important;
        overflow      : hidden !important;
        box-shadow    : none !important;
    }

    /* ── Toolbar: putih bersih, tipis, minimalis ──────────────── */
    .tox .tox-toolbar,
    .tox .tox-toolbar__overflow,
    .tox .tox-toolbar__primary {
        background    : #ffffff !important;
        border        : none !important;
        border-bottom : 1px solid #e5e7eb !important;
        padding       : 5px 8px !important;
        gap           : 2px !important;
    }

    /* Toolbar button — ikon gelap netral */
    .tox .tox-tbtn {
        border-radius : 5px !important;
        color         : #4b5563 !important;
        border        : none !important;
        background    : transparent !important;
        width         : 30px !important;
        height        : 30px !important;
        transition    : background 0.15s, color 0.15s !important;
    }
    .tox .tox-tbtn svg {
        fill : #4b5563 !important;
    }
    .tox .tox-tbtn:hover:not([disabled]) {
        background    : #f3f4f6 !important;
        color         : #111827 !important;
    }
    .tox .tox-tbtn:hover:not([disabled]) svg {
        fill : #111827 !important;
    }

    /* Tombol aktif/tertekan — biru muda */
    .tox .tox-tbtn--enabled,
    .tox .tox-tbtn--enabled:hover {
        background    : #eff6ff !important;
        color         : #1d4ed8 !important;
    }
    .tox .tox-tbtn--enabled svg,
    .tox .tox-tbtn--enabled:hover svg {
        fill : #1d4ed8 !important;
    }

    /* Dropdown "Blocks" (Paragraph / Heading) */
    .tox .tox-tbtn.tox-tbtn--select {
        width         : auto !important;
        min-width     : 110px !important;
        padding       : 0 8px !important;
        border        : 1px solid #e5e7eb !important;
        border-radius : 5px !important;
        background    : #ffffff !important;
        color         : #374151 !important;
        font-size     : 0.85rem !important;
        font-weight   : 500 !important;
    }
    .tox .tox-tbtn.tox-tbtn--select:hover {
        background    : #f9fafb !important;
        border-color  : #d1d5db !important;
    }

    /* Separator "|" — garis tipis vertikal */
    .tox .tox-toolbar__separator {
        border-left   : 1px solid #e5e7eb !important;
        height        : 20px !important;
        margin        : 0 5px !important;
        align-self    : center !important;
    }

    /* ── Area tulis ───────────────────────────────────────────── */
    .tox .tox-edit-area {
        border : none !important;
    }
    .tox .tox-edit-area__iframe {
        background : #ffffff !important;
    }

    /* Sembunyikan status bar */
    .tox .tox-statusbar {
        display : none !important;
    }

    /* ── Focus ring — warna brand teal ───────────────────────── */
    .tox.tox-tinymce:focus-within,
    .tox.tox-tinymce--focused {
        border-color : #148F9A !important;
        box-shadow   : 0 0 0 3px rgba(20, 143, 154, 0.15) !important;
    }

    /* Sembunyikan container notifikasi jika ada */
    .tox-notifications-container {
        display : none !important;
    }
</style>
@endpush
