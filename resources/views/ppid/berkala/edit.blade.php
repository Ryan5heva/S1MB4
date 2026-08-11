@extends('layouts.app')

@section('title', 'Edit — ' . $ppid->nama_informasi)
@section('page-title', $ppid->hasDokumen() ? 'Edit Dokumen/Link' : 'Tambah Dokumen/Link')
@section('page-subtitle', ($ppid->jenisDokumen?->jenis_dokumen ?? $ppid->kategori ?? 'PPID') . ' — ' . Str::limit($ppid->nama_informasi, 60))

@section('content')
<div>

    {{-- Back --}}
    <div class="mb-4">
        <a href="{{ route('ppid.berkala.index', ['jenis_dokumen_id' => $ppid->id_jenis_dokumen]) }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Kembali ke PPID
        </a>
    </div>

    <div class="bg-white rounded-2xl p-8" style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.06);">

        {{-- Info header --}}
        <div class="mb-6 pb-5 border-b border-gray-100">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(20,143,154,0.1);">
                    <i class="bi bi-file-earmark-text" style="color:#148F9A;font-size:1.1rem;"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">
                        {{ $ppid->jenisDokumen?->jenis_dokumen ?? $ppid->kategori ?? 'PPID' }}
                    </p>
                    <h3 class="text-sm font-semibold text-gray-800">{{ $ppid->nama_informasi }}</h3>
                    @if($ppid->is_fixed)
                        <span class="inline-flex items-center gap-1 text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded px-2 py-0.5 mt-1.5">
                            <i class="bi bi-lock-fill" style="font-size:.6rem;"></i>
                            Nama Informasi bersifat tetap dan tidak dapat diubah
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Validation errors --}}
        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <p class="text-sm font-semibold text-red-700 mb-1">
                <i class="bi bi-exclamation-triangle me-1"></i> Terdapat kesalahan:
            </p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-600">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST"
              action="{{ route('ppid.berkala.update', $ppid) }}"
              enctype="multipart/form-data"
              class="space-y-5"
              id="formEditBerkala">
            @csrf
            @method('PUT')

            {{-- Nama Informasi (readonly) --}}
            <div>
                <label class="form-label">Nama Informasi</label>
                <input type="text" value="{{ $ppid->nama_informasi }}"
                       class="form-input" readonly
                       style="background:#f9fafb; color:#9ca3af; cursor:not-allowed;">
            </div>

            {{-- Kategori (readonly, informatif) --}}
            <div>
                <label class="form-label">Kategori</label>
                <input type="text"
                       value="{{ $ppid->jenisDokumen?->jenis_dokumen ?? $ppid->kategori ?? '—' }}"
                       class="form-input" readonly
                       style="background:#f9fafb; color:#9ca3af; cursor:not-allowed;">
            </div>

            {{-- Tahun --}}
            <div>
                <label for="tahun" class="form-label">
                    Tahun <span class="text-gray-400 text-xs font-normal">(opsional)</span>
                </label>
                <input type="number" id="tahun" name="tahun"
                       value="{{ old('tahun', $ppid->tahun) }}"
                       min="2000" max="{{ date('Y') + 5 }}"
                       placeholder="Contoh: {{ date('Y') }}"
                       class="form-input"
                       {{ $ppid->is_fixed ? 'readonly style=background:#f9fafb;color:#9ca3af;cursor:not-allowed;' : '' }}>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="form-label">
                    Deskripsi <span class="text-gray-400 text-xs font-normal">(opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          placeholder="Keterangan singkat tentang dokumen/link ini..."
                          class="form-input resize-y">{{ old('deskripsi', $ppid->deskripsi) }}</textarea>
            </div>

            {{-- Jenis --}}
            <div>
                <label class="form-label">Jenis <span class="text-red-500">*</span></label>
                <div class="flex gap-5 mt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jenis" value="dokumen"
                               class="w-4 h-4"
                               {{ old('jenis', $ppid->jenis ?? 'dokumen') === 'dokumen' ? 'checked' : '' }}
                               onchange="toggleJenis(this.value)">
                        <span class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <i class="bi bi-file-earmark-text text-blue-500"></i> Dokumen
                        </span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jenis" value="link"
                               class="w-4 h-4"
                               {{ old('jenis', $ppid->jenis) === 'link' ? 'checked' : '' }}
                               onchange="toggleJenis(this.value)">
                        <span class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <i class="bi bi-link-45deg text-indigo-500"></i> Link / URL
                        </span>
                    </label>
                </div>
            </div>

            {{-- Upload Dokumen --}}
            <div id="fieldDokumen" class="{{ old('jenis', $ppid->jenis) === 'link' ? 'hidden' : '' }}">
                <label for="file" class="form-label">
                    Upload Dokumen {{ $ppid->file ? '' : '<span class="text-red-500">*</span>' }}
                </label>

                {{-- File lama --}}
                @if($ppid->jenis === 'dokumen' && $ppid->file)
                <div class="mb-3 flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-2.5">
                    <i class="bi bi-file-earmark-check text-blue-500 text-lg flex-shrink-0"></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-blue-400 mb-0.5">File saat ini:</p>
                        <a href="{{ asset('storage/' . $ppid->file) }}" target="_blank"
                           class="text-sm text-blue-700 hover:underline truncate block">
                            {{ $ppid->file_name }}
                        </a>
                    </div>
                    <span class="text-xs text-blue-400 flex-shrink-0">Unggah baru untuk mengganti</span>
                </div>
                @endif

                <div id="dropZone"
                     class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center cursor-pointer hover:border-teal-400 hover:bg-teal-50/30 transition-all">
                    <i class="bi bi-cloud-upload" style="font-size:1.75rem;color:#9ca3af;"></i>
                    <p class="text-sm text-gray-500 mt-1.5">Klik untuk memilih file</p>
                    <p class="text-xs text-gray-400 mt-0.5">PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX — Maks. 500 KB</p>
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
            <div id="fieldLink" class="{{ old('jenis', $ppid->jenis) !== 'link' ? 'hidden' : '' }}">
                <label for="url" class="form-label">URL / Link <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="bi bi-link-45deg text-gray-400"></i>
                    </div>
                    <input type="url" id="url" name="url"
                           value="{{ old('url', $ppid->url) }}"
                           placeholder="https://..."
                           class="form-input"
                           style="padding-left:2.25rem;">
                </div>
            </div>

            {{-- Status + Tanggal Publish --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="form-input" required>
                        <option value="draft"   {{ old('status', $ppid->status) === 'draft'   ? 'selected' : '' }}>Draft</option>
                        <option value="publish" {{ old('status', $ppid->status) === 'publish' ? 'selected' : '' }}>Publish</option>
                    </select>
                </div>
                <div>
                    <label for="published_at" class="form-label">Tanggal Publish</label>
                    <input type="date" id="published_at" name="published_at"
                           value="{{ old('published_at', $ppid->published_at?->format('Y-m-d')) }}"
                           class="form-input">
                </div>
            </div>

            {{-- Meta info --}}
            @if($ppid->hasDokumen() && $ppid->user)
            <p class="text-xs text-gray-400">
                Terakhir diubah: {{ $ppid->updated_at->format('d M Y H:i') }} oleh {{ $ppid->user->name }}
            </p>
            @endif

            {{-- Tombol --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" id="saveBtn" class="btn-primary">
                    <i class="bi bi-check-lg"></i>
                    {{ $ppid->hasDokumen() ? 'Simpan Perubahan' : 'Simpan Dokumen/Link' }}
                </button>
                <a href="{{ route('ppid.berkala.index', ['jenis_dokumen_id' => $ppid->id_jenis_dokumen]) }}"
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
    }

    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('file');

    if (dropZone) dropZone.addEventListener('click', () => fileInput.click());
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (this.files[0]) {
                document.getElementById('fileName').textContent = this.files[0].name;
                document.getElementById('fileDisplay').classList.remove('hidden');
            }
        });
    }

    function clearFile() {
        if (fileInput) fileInput.value = '';
        document.getElementById('fileDisplay').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const checked = document.querySelector('input[name="jenis"]:checked');
        if (checked) toggleJenis(checked.value);
    });
</script>
@endpush
