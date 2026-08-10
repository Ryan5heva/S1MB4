<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi untuk menambah data baru di PPID Informasi Berkala.
 * Kategori dipilih via dropdown (id_jenis_dokumen), bukan lagi hardcode ke Ketenagakerjaan.
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
            'id_jenis_dokumen' => ['required', 'integer', 'exists:jenis_dokumen,id'],
            'nama_informasi'   => ['required', 'string', 'max:255'],
            'deskripsi'        => ['nullable', 'string'],
            'tahun'            => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 5)],
            'jenis'            => ['required', 'in:dokumen,link'],

            'file'             => [
                'nullable',
                'required_if:jenis,dokumen',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
                'max:1024',
            ],

            'url'              => [
                'nullable',
                'required_if:jenis,link',
                'url',
                'max:2048',
            ],

            'status'           => ['required', 'in:publish,draft'],
            'urutan'           => ['nullable', 'integer', 'min:0'],
            'published_at'     => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_jenis_dokumen.required' => 'Kategori wajib dipilih.',
            'id_jenis_dokumen.exists'   => 'Kategori yang dipilih tidak valid.',
            'nama_informasi.required'   => 'Nama informasi wajib diisi.',
            'tahun.integer'             => 'Tahun harus berupa angka.',
            'tahun.min'                 => 'Tahun tidak boleh kurang dari 2000.',
            'jenis.required'            => 'Jenis informasi wajib dipilih.',
            'jenis.in'                  => 'Jenis informasi tidak valid.',
            'file.required_if'          => 'File dokumen wajib diunggah jika jenis adalah Dokumen.',
            'file.mimes'                => 'Format file tidak didukung. Gunakan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX.',
            'file.max'                  => 'Ukuran file tidak boleh melebihi 1 MB.',
            'url.required_if'           => 'URL wajib diisi jika jenis adalah Link.',
            'url.url'                   => 'Format URL tidak valid (harus diawali http:// atau https://).',
            'status.required'           => 'Status publikasi wajib dipilih.',
        ];
    }
}