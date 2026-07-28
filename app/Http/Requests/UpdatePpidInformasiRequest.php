<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi untuk mengupdate dokumen/link pada baris Informasi Berkala.
 * Berlaku untuk fixed items maupun Ketenagakerjaan.
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
            'jenis'        => ['required', 'in:dokumen,link'],

            // File opsional saat update — hanya replace jika ada file baru
            'file'         => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
                'max:10240',
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
            'jenis.required'   => 'Jenis informasi wajib dipilih.',
            'jenis.in'         => 'Jenis informasi tidak valid.',
            'file.mimes'       => 'Format file tidak didukung. Gunakan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.',
            'file.max'         => 'Ukuran file tidak boleh melebihi 10 MB.',
            'url.required_if'  => 'URL wajib diisi jika jenis adalah Link.',
            'url.url'          => 'Format URL tidak valid (harus diawali http:// atau https://).',
            'status.required'  => 'Status publikasi wajib dipilih.',
        ];
    }
}
