{{--
    partials/ckeditor.blade.php
    ───────────────────────────
    CKEditor 5 — Classic Build (CDN, tidak butuh npm/build step).
    Di-include oleh: berita/create.blade.php dan berita/edit.blade.php

    Penggunaan:
        @include('partials.ckeditor', ['fieldId' => 'konten', 'initialContent' => old('konten', $berita->konten ?? '')])

    Parameter:
        $fieldId         — ID dari <textarea> target (default: 'konten')
        $initialContent  — Konten awal editor (opsional, default: '')
--}}

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
(function () {
    'use strict';

    const fieldId  = {{ Js::from($fieldId ?? 'konten') }};
    const textarea = document.getElementById(fieldId);

    if (!textarea) return;

    // Sembunyikan textarea asli — nilainya diisi otomatis saat form submit
    textarea.style.display = 'none';

    // Container tempat editor dirender (div tepat setelah textarea)
    const editorContainer = document.getElementById('ck-editor-' + fieldId);

    ClassicEditor
        .create(editorContainer, {
            initialData: textarea.value,   // ← load existing content (edit page)
            toolbar: {
                items: [
                    'undo', 'redo', '|',
                    'heading', '|',
                    'bold', 'italic', '|',
                    'alignment:left', 'alignment:center', 'alignment:right', 'alignment:justify', '|',
                    'outdent', 'indent'
                ],
                shouldNotGroupWhenFull: true
            },
            alignment: {
                options: ['left', 'center', 'right', 'justify']
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1',  view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2',  view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3',  view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                ]
            },
            language: 'en',
        })
        .then(editor => {
            // Saat form disubmit, salin HTML dari editor ke textarea tersembunyi
            textarea.closest('form').addEventListener('submit', () => {
                textarea.value = editor.getData();
            });
        })
        .catch(error => {
            console.error('[CKEditor]', error);
        });
})();
</script>

{{-- ─── Styling CKEditor: clean minimal toolbar (sesuai screenshot) ─── --}}
<style>
    /* ── Wrapper keseluruhan editor ──────────────────────────── */
    .ck.ck-editor {
        border: 1px solid #d1d5db !important;
        border-radius: 0.625rem !important;
        overflow: hidden;
        box-shadow: none !important;
    }

    /* ── Toolbar: putih bersih, tipis, minimalis ─────────────── */
    .ck.ck-toolbar {
        background: #ffffff !important;
        border: none !important;
        border-bottom: 1px solid #e5e7eb !important;
        border-radius: 0 !important;
        padding: 6px 10px !important;
        gap: 2px !important;
    }

    /* Tombol toolbar — ukuran & warna ikon */
    .ck.ck-toolbar .ck-button {
        border-radius: 5px !important;
        color: #4b5563 !important;
        border: none !important;
        background: transparent !important;
        padding: 5px 6px !important;
        min-width: 28px !important;
        min-height: 28px !important;
        transition: background 0.15s, color 0.15s !important;
    }
    .ck.ck-toolbar .ck-button:hover:not(.ck-disabled) {
        background: #f3f4f6 !important;
        color: #111827 !important;
    }
    .ck.ck-toolbar .ck-button.ck-on {
        background: #eff6ff !important;
        color: #1d4ed8 !important;
    }

    /* Dropdown "Paragraph / Heading" */
    .ck.ck-toolbar .ck-heading-dropdown .ck-button.ck-dropdown__button {
        min-width: 110px !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 5px !important;
        background: #ffffff !important;
        color: #374151 !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        padding: 4px 10px !important;
    }
    .ck.ck-toolbar .ck-heading-dropdown .ck-button.ck-dropdown__button:hover {
        background: #f9fafb !important;
        border-color: #d1d5db !important;
    }

    /* Separator "|" — garis tipis vertikal */
    .ck.ck-toolbar .ck-toolbar__separator {
        background: #e5e7eb !important;
        width: 1px !important;
        height: 20px !important;
        margin: 0 6px !important;
        align-self: center !important;
    }

    /* ── Area tulis ──────────────────────────────────────────── */
    .ck.ck-editor__main > .ck-editor__editable {
        background: #ffffff !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        min-height: 300px;
        max-height: 600px;
        overflow-y: auto;
        font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        font-size: 0.9375rem;
        line-height: 1.8;
        color: #1f2937;
        padding: 1.125rem 1.375rem !important;
    }

    /* Focus ring — warna brand teal */
    .ck.ck-editor__main > .ck-editor__editable.ck-focused {
        outline: none !important;
        box-shadow: none !important;
    }
    .ck.ck-editor:focus-within {
        border-color: #148F9A !important;
        box-shadow: 0 0 0 3px rgba(20, 143, 154, 0.15) !important;
    }

    /* Paragraph spacing di dalam editor */
    .ck-editor__editable p {
        margin-bottom: 0.75rem;
    }
</style>
@endpush
