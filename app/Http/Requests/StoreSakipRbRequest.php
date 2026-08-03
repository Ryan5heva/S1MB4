<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi untuk menambah dokumen SAKIP-RB baru.
 * Field jenis_input (dokumen/link) dipakai sebagai discriminator
 * antara upload file dan input URL.
 */
class StoreSakipRbRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'jenis_dokumen' => ['required', 'string', 'max:255'],
            'klasifikasi'   => ['nullable', 'string', 'max:255'],
            'tahun'         => ['required', 'integer', 'min:2000', 'max:2100'],
            'status'        => ['required', 'in:0,1'],

            // Discriminator: apakah admin mengunggah file atau memasukkan URL?
            'jenis_input'   => ['required', 'in:dokumen,link'],

            'file'          => [
                'nullable',
                'required_if:jenis_input,dokumen',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120',
            ],

            'url'           => [
                'nullable',
                'required_if:jenis_input,link',
                'url',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_dokumen.required' => 'Jenis/nama dokumen wajib diisi.',
            'tahun.required'         => 'Tahun dokumen wajib diisi.',
            'tahun.min'              => 'Tahun tidak valid (minimal 2000).',
            'tahun.max'              => 'Tahun tidak valid (maksimal 2100).',
            'status.required'        => 'Status wajib dipilih.',
            'status.in'              => 'Nilai status tidak valid.',
            'jenis_input.required'   => 'Jenis input (dokumen/link) wajib dipilih.',
            'jenis_input.in'         => 'Jenis input tidak valid.',
            'file.required_if'       => 'File dokumen wajib diunggah jika memilih jenis Dokumen.',
            'file.mimes'             => 'Format file tidak didukung. Gunakan: PDF, DOC, DOCX, JPG, JPEG, PNG.',
            'file.max'               => 'Ukuran file tidak boleh melebihi 5 MB.',
            'url.required_if'        => 'URL wajib diisi jika memilih jenis Link.',
            'url.url'                => 'Format URL tidak valid (harus diawali http:// atau https://).',
        ];
    }
}
