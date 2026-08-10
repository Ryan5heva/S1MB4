import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/js/app.js',
                'resources/js/sidebar.js',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),

        // ── Catatan: TinyMCE self-hosted ─────────────────────────────────────
        // Asset TinyMCE (skins, plugins, icons, dll) disalin dari node_modules
        // ke public/tinymce lewat npm prebuild/predev hook (scripts/copy-tinymce.mjs).
        // Tidak perlu vite plugin tambahan untuk ini.
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
