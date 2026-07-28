<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi untuk menambah data baru di kategori Ketenagakerjaan.
 * Satu-satunya kategori yang membolehkan admin menambah baris baru.
 */
class StorePpidInformasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nama_informasi' => ['required', 'string', 'max:255'],
            'deskripsi'      => ['nullable', 'string'],
            'jenis'          => ['required', 'in:dokumen,link'],

            'file'           => [
                'nullable',
                'required_if:jenis,dokumen',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
                'max:10240',
            ],

            'url'            => [
                'nullable',
                'required_if:jenis,link',
                'url',
                'max:2048',
            ],

            'status'         => ['required', 'in:publish,draft'],
            'urutan'         => ['nullable', 'integer', 'min:0'],
            'published_at'   => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_informasi.required' => 'Nama informasi wajib diisi.',
            'jenis.required'          => 'Jenis informasi wajib dipilih.',
            'jenis.in'                => 'Jenis informasi tidak valid.',
            'file.required_if'        => 'File dokumen wajib diunggah jika jenis adalah Dokumen.',
            'file.mimes'              => 'Format file tidak didukung. Gunakan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.',
            'file.max'                => 'Ukuran file tidak boleh melebihi 10 MB.',
            'url.required_if'         => 'URL wajib diisi jika jenis adalah Link.',
            'url.url'                 => 'Format URL tidak valid (harus diawali http:// atau https://).',
            'status.required'         => 'Status publikasi wajib dipilih.',
        ];
    }
}
