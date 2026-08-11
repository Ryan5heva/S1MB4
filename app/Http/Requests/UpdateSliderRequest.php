<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:1024',
            'urutan' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'gambar.required' => 'Gambar wajib diunggah.',
            'gambar.image'    => 'File harus berupa gambar.',
            'gambar.mimes'    => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'gambar.max'      => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}