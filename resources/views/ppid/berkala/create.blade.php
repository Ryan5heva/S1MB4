@extends('layouts.app')

@section('title', 'Tambah Data PPID')
@section('page-title', 'Tambah Data PPID')
@section('page-subtitle', 'PPID Informasi Berkala — Tambah item baru ke kategori')

@section('content')
<div>

    {{-- Back --}}
    <div class="mb-4">
        <a href="{{ route('ppid.berkala.index', ['jenis_dokumen_id' => $jenisDokumenId]) }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Kembali ke PPID
        </a>
    </div>

    <div class="bg-white rounded-2xl p-8" style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.06);">

        {{-- Header --}}
        <div class="mb-6 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(20,143,154,0.1);">
                    <i class="bi bi-plus-circle" style="color:#148F9A;font-size:1.1rem;"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400">PPID Informasi Berkala</p>
                    <h3 class="text-sm font-semibold text-gray-800">Tambah Data Baru</h3>
                </div>
            </div>
        </div>

        {{-- Validation errors --}}
        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <p class="text-sm font-semibold text-red-700 mb-1">
                <i class="bi bi-exclamation-triangle"></i> Terdapat kesalahan:
            </p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-600">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST"
              action="{{ route('ppid.berkala.store') }}"
              enctype="multipart/form-data"
              class="space-y-5"
              id="formTambahPpid">
            @csrf

            {{-- Kategori / Jenis Dokumen --}}
            <div>
                <label for="id_jenis_dokumen" class="form-label">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="id_jenis_dokumen" id="id_jenis_dokumen"
                        class="form-input" required
                        onchange="toggleTahun(this.value)">
                    <option value="">— Pilih Kategori —</option>
                    @foreach($jenisDokumenList as $jd)
                        <option value="{{ $jd->id }}"
                            data-klasifikasi="{{ $jd->klasifikasi }}"
                            {{ (old('id_jenis_dokumen', $jenisDokumenId) == $jd->id) ? 'selected' : '' }}>
                            {{ $jd->jenis_dokumen }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Pilih kategori PPID untuk data ini.</p>
            </div>

            {{-- Nama Informasi --}}
            <div>
                <label for="nama_informasi" class="form-label">
                    Nama Informasi <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_informasi" name="nama_informasi"
                       value="{{ old('nama_informasi') }}"
                       placeholder="Nama dokumen atau informasi"
                       class="form-input" required>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="form-label">
                    Deskripsi <span class="text-gray-400 text-xs font-normal">(opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          placeholder="Keterangan singkat..."
                          class="form-input resize-y">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Tahun (opsional, tampil selalu tapi wajib jika klasifikasi = 'sakip') --}}
            <div id="fieldTahun">
                <label for="tahun" class="form-label">
                    Tahun <span class="text-gray-400 text-xs font-normal" id="tahunNote">(opsional)</span>
                </label>
                <input type="number" id="tahun" name="tahun"
                       value="{{ old('tahun', date('Y')) }}"
                       min="2000" max="{{ date('Y') + 5 }}"
                       placeholder="Contoh: {{ date('Y') }}"
                       class="form-input">
            </div>

            {{-- Jenis --}}
            <div>
                <label class="form-label">Jenis <span class="text-red-500">*</span></label>
                <div class="flex gap-5 mt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jenis" value="dokumen"
                               class="w-4 h-4"
                               {{ old('jenis', 'dokumen') === 'dokumen' ? 'checked' : '' }}
                               onchange="toggleJenis(this.value)">
                        <span class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <i class="bi bi-file-earmark-text text-blue-500"></i> Dokumen
                        </span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jenis" value="link"
                               class="w-4 h-4"
                               {{ old('jenis') === 'link' ? 'checked' : '' }}
                               onchange="toggleJenis(this.value)">
                        <span class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <i class="bi bi-link-45deg text-indigo-500"></i> Link / URL
                        </span>
                    </label>
                </div>
            </div>

            {{-- Upload Dokumen --}}
            <div id="fieldDokumen" class="{{ old('jenis') === 'link' ? 'hidden' : '' }}">
                <label for="file" class="form-label">Upload Dokumen <span class="text-red-500">*</span></label>
                <div id="dropZone"
                     class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center cursor-pointer hover:border-teal-400 hover:bg-teal-50/30 transition-all">
                    <i class="bi bi-cloud-upload" style="font-size:1.75rem;color:#9ca3af;"></i>
                    <p class="text-sm text-gray-500 mt-1.5">Klik untuk memilih file</p>
                    <p class="text-xs text-gray-400 mt-0.5">PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX — Maks. 1 MB</p>
                    <input type="file" id="file" name="file"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                           class="hidden">
                </div>
                <div id="fileDisplay" class="mt-2 hidden">
                    <span class="inline-flex items-center gap-2 text-sm text-teal-700 bg-teal-50 border border-teal-200 rounded-lg px-3 py-1.5">
                        <i class="bi bi-file-earmark-check"></i>
                        <span id="fileName"></span>
                        <button type="button" onclick="clearFile()" class="text-teal-400 hover:text-red-500">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </span>
                </div>
            </div>

            {{-- Input URL --}}
            <div id="fieldLink" class="{{ old('jenis') !== 'link' ? 'hidden' : '' }}">
                <label for="url" class="form-label">URL / Link <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="bi bi-link-45deg text-gray-400"></i>
                    </div>
                    <input type="url" id="url" name="url"
                           value="{{ old('url') }}"
                           placeholder="https://..."
                           class="form-input"
                           style="padding-left:2.25rem;">
                </div>
            </div>

            {{-- Status + Tanggal --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="form-input" required>
                        <option value="draft"   {{ old('status', 'draft') === 'draft'   ? 'selected' : '' }}>Draft</option>
                        <option value="publish" {{ old('status') === 'publish' ? 'selected' : '' }}>Publish</option>
                    </select>
                </div>
                <div>
                    <label for="published_at" class="form-label">Tanggal Publish</label>
                    <input type="date" id="published_at" name="published_at"
                           value="{{ old('published_at') }}"
                           class="form-input">
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" id="submitBtn" class="btn-primary">
                    <i class="bi bi-check-lg"></i> Simpan Data
                </button>
                <a href="{{ route('ppid.berkala.index', ['jenis_dokumen_id' => $jenisDokumenId]) }}"
                   class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleJenis(val) {
        document.getElementById('fieldDokumen').classList.toggle('hidden', val !== 'dokumen');
        document.getElementById('fieldLink').classList.toggle('hidden', val !== 'link');
        document.getElementById('url').required = (val === 'link');
        if (val !== 'dokumen') clearFile();
    }

    function toggleTahun(selectVal) {
        const opt = document.querySelector('#id_jenis_dokumen option[value="' + selectVal + '"]');
        const klasifikasi = opt ? opt.dataset.klasifikasi : '';
        const note = document.getElementById('tahunNote');
        if (klasifikasi && klasifikasi.toLowerCase() === 'sakip') {
            note.textContent = '(wajib untuk SAKIP)';
            document.getElementById('tahun').required = true;
        } else {
            note.textContent = '(opsional)';
            document.getElementById('tahun').required = false;
        }
    }

    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('file');

    dropZone.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', function () {
        if (this.files[0]) {
            document.getElementById('fileName').textContent = this.files[0].name;
            document.getElementById('fileDisplay').classList.remove('hidden');
        }
    });

    function clearFile() {
        fileInput.value = '';
        document.getElementById('fileDisplay').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const checked = document.querySelector('input[name="jenis"]:checked');
        if (checked) toggleJenis(checked.value);

        // Init toggleTahun berdasar kategori yang sudah dipilih
        const sel = document.getElementById('id_jenis_dokumen');
        if (sel && sel.value) toggleTahun(sel.value);
    });
</script>
@endpush
