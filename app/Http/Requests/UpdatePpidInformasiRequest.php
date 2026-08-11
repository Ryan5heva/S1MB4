<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi untuk mengupdate dokumen/link pada baris Informasi Berkala.
 * Berlaku untuk semua kategori PPID.
 * Nama Informasi TIDAK divalidasi karena tidak boleh diubah via form.
 */
class UpdatePpidInformasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'deskripsi'    => ['nullable', 'string'],
            'tahun'        => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 5)],
            'jenis'        => ['required', 'in:dokumen,link'],

            // File opsional saat update — hanya replace jika ada file baru
            'file'         => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
                'max:500',
            ],

            'url'          => [
                'nullable',
                'required_if:jenis,link',
                'url',
                'max:2048',
            ],

            'status'       => ['required', 'in:publish,draft'],
            'urutan'       => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'tahun.integer'    => 'Tahun harus berupa angka.',
            'tahun.min'        => 'Tahun tidak boleh kurang dari 2000.',
            'jenis.required'   => 'Jenis informasi wajib dipilih.',
            'jenis.in'         => 'Jenis informasi tidak valid.',
            'file.mimes'       => 'Format file tidak didukung. Gunakan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.',
            'file.max'         => 'Ukuran file tidak boleh melebihi 500 KB.',
            'url.required_if'  => 'URL wajib diisi jika jenis adalah Link.',
            'url.url'          => 'Format URL tidak valid (harus diawali http:// atau https://).',
            'status.required'  => 'Status publikasi wajib dipilih.',
        ];
    }
}