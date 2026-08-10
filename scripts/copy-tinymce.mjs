/**
 * scripts/copy-tinymce.mjs
 * ─────────────────────────
 * Script pre-build/pre-dev: menyalin seluruh asset TinyMCE dari
 * node_modules/tinymce ke public/tinymce agar bisa diakses browser
 * sebagai file statis (self-hosted, tanpa CDN / API key).
 *
 * Dipanggil otomatis lewat npm hook:
 *   "prebuild": "node scripts/copy-tinymce.mjs"
 *   "predev"  : "node scripts/copy-tinymce.mjs"
 */

import { cpSync, existsSync, mkdirSync } from 'fs';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const root      = resolve(__dirname, '..');

const src = resolve(root, 'node_modules', 'tinymce');
const dst = resolve(root, 'public', 'tinymce');

if (!existsSync(src)) {
    console.error('[copy-tinymce] ERROR: node_modules/tinymce tidak ditemukan. Jalankan: npm install tinymce');
    process.exit(1);
}

if (!existsSync(dst)) {
    mkdirSync(dst, { recursive: true });
}

cpSync(src, dst, { recursive: true, force: true });

console.log('[copy-tinymce] ✓ TinyMCE asset berhasil disalin ke public/tinymce');
