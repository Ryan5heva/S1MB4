<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSliderRequest extends FormRequest
{
    /**
     * Hanya user yang sudah login yang boleh mengupload gambar slider.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk upload gambar slider.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'url_tujuan' => ['nullable', 'url', 'max:255'],
            'gambar'     => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'urutan'     => ['nullable', 'integer', 'min:0'],
        ];
    }
}

