<?php

namespace App\Http\Requests\Admin;

use App\Models\MediaFile;
use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
    private const MAX_FILES = 10;
    private const MAX_KILOBYTES = 8192;

    public function authorize(): bool
    {
        return $this->user()->can('create', MediaFile::class);
    }

    public function rules(): array
    {
        return [
            'files' => ['required', 'array', 'min:1', 'max:'.self::MAX_FILES],
            'files.*' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:'.self::MAX_KILOBYTES],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ];
    }
}
