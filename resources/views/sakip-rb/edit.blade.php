@extends('layouts.app')

@section('title', 'Edit — ' . Str::limit($sakipRb->jenis_dokumen, 50))
@section('page-title', 'Edit Dokumen SAKIP-RB')
@section('page-subtitle', 'SAKIP-RB — Edit dokumen yang sudah ada')

@section('content')
<div>

    {{-- Back --}}
    <div class="mb-4">
        <a href="{{ route('sakip-rb.index', ['tahun' => $sakipRb->tahun]) }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar SAKIP-RB
        </a>
    </div>

    <div class="bg-white rounded-2xl p-8" style="box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.06);">

        {{-- Info header --}}
        <div class="mb-6 pb-5 border-b border-gray-100">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(20,143,154,0.1);">
                    <i class="bi bi-clipboard-check" style="color:#148F9A;font-size:1.1rem;"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-400 mb-0.5">SAKIP-RB — Edit Dokumen</p>
                    <h3 class="text-sm font-semibold text-gray-800 leading-snug">{{ $sakipRb->jenis_dokumen }}</h3>
                    <span class="text-xs text-gray-400">Tahun {{ $sakipRb->tahun }}</span>
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

        @php
            // Tentukan jenis_input awal berdasarkan data yang sudah ada
            $jenisInputAwal = old('jenis_input', $sakipRb->url ? 'link' : 'dokumen');
        @endphp

        <form method="POST"
              action="{{ route('sakip-rb.update', $sakipRb) }}"
              enctype="multipart/form-data"
              class="space-y-5"
              id="formEditSakipRb">
            @csrf
            @method('PUT')

            {{-- Jenis Dokumen --}}
            <div>
                <label for="jenis_dokumen" class="form-label">
                    Jenis/Nama Dokumen <span class="text-red-500">*</span>
                </label>
                <input type="text" id="jenis_dokumen" name="jenis_dokumen"
                       value="{{ old('jenis_dokumen', $sakipRb->jenis_dokumen) }}"
                       placeholder="cth. Perjanjian Kinerja, Laporan Kinerja..."
                       class="form-input" required>
            </div>

            {{-- Klasifikasi --}}
            <div>
                <label for="klasifikasi" class="form-label">
                    Klasifikasi <span class="text-gray-400 text-xs font-normal">(opsional)</span>
                </label>
                <input type="text" id="klasifikasi" name="klasifikasi"
                       value="{{ old('klasifikasi', $sakipRb->klasifikasi) }}"
                       placeholder="cth. SAKIP, Reformasi Birokrasi..."
                       class="form-input">
            </div>

            {{-- Tahun --}}
            <div>
                <label for="tahun" class="form-label">
                    Tahun <span class="text-red-500">*</span>
                </label>
                <input type="number" id="tahun" name="tahun"
                       value="{{ old('tahun', $sakipRb->tahun) }}"
                       min="2000" max="2100"
                       class="form-input" required>
            </div>

            {{-- Jenis Input (Dokumen / Link) --}}
            <div>
                <label class="form-label">Jenis Input <span class="text-red-500">*</span></label>
                <div class="flex gap-5 mt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jenis_input" value="dokumen"
                               class="w-4 h-4"
                               {{ $jenisInputAwal === 'dokumen' ? 'checked' : '' }}
                               onchange="toggleJenisInput(this.value)">
                        <span class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <i class="bi bi-file-earmark-text text-blue-500"></i> Dokumen
                        </span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jenis_input" value="link"
                               class="w-4 h-4"
                               {{ $jenisInputAwal === 'link' ? 'checked' : '' }}
                               onchange="toggleJenisInput(this.value)">
                        <span class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <i class="bi bi-link-45deg text-indigo-500"></i> Link / URL
                        </span>
                    </label>
                </div>
            </div>

            {{-- Upload Dokumen --}}
            <div id="fieldDokumen" class="{{ $jenisInputAwal === 'link' ? 'hidden' : '' }}">
                <label for="file" class="form-label">
                    Upload File
                    @if(!$sakipRb->file)
                        <span class="text-gray-400 text-xs font-normal">(opsional — belum ada file)</span>
                    @else
                        <span class="text-gray-400 text-xs font-normal">(kosongkan jika tidak ingin mengganti)</span>
                    @endif
                </label>

                {{-- File lama --}}
                @if($sakipRb->file)
                <div class="mb-3 flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-2.5">
                    <i class="bi bi-file-earmark-check text-blue-500 text-lg flex-shrink-0"></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-blue-400 mb-0.5">File saat ini:</p>
                        <a href="{{ asset('storage/' . $sakipRb->file) }}" target="_blank"
                           class="text-sm text-blue-700 hover:underline truncate block">
                            {{ $sakipRb->file_name }}
                        </a>
                    </div>
                    <span class="text-xs text-blue-400 flex-shrink-0">Unggah baru untuk mengganti</span>
                </div>
                @endif

                <div id="dropZone"
                     class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center cursor-pointer hover:border-teal-400 hover:bg-teal-50/30 transition-all">
                    <i class="bi bi-cloud-upload" style="font-size:1.75rem;color:#9ca3af;"></i>
                    <p class="text-sm text-gray-500 mt-1.5">Klik untuk memilih file</p>
                    <p class="text-xs text-gray-400 mt-0.5">PDF, DOC, DOCX, JPG, JPEG, PNG — Maks. 5 MB</p>
                    <input type="file" id="file" name="file"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
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
            <div id="fieldLink" class="{{ $jenisInputAwal !== 'link' ? 'hidden' : '' }}">
                <label for="url" class="form-label">URL / Link <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="bi bi-link-45deg text-gray-400"></i>
                    </div>
                    <input type="url" id="url" name="url"
                           value="{{ old('url', $sakipRb->url) }}"
                           placeholder="https://..."
                           class="form-input"
                           style="padding-left:2.25rem;">
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" class="form-input" required>
                    <option value="1" {{ old('status', $sakipRb->status) === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status', $sakipRb->status) === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Meta info --}}
            @if($sakipRb->user)
            <p class="text-xs text-gray-400">
                Terakhir diubah: {{ $sakipRb->updated_at->format('d M Y H:i') }} oleh {{ $sakipRb->user->name }}
            </p>
            @endif

            {{-- Tombol --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" id="saveBtn" class="btn-primary">
                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                </button>
                <a href="{{ route('sakip-rb.index', ['tahun' => $sakipRb->tahun]) }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleJenisInput(val) {
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
        const checked = document.querySelector('input[name="jenis_input"]:checked');
        if (checked) toggleJenisInput(checked.value);
    });
</script>
@endpush
