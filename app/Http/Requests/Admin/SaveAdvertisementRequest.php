<?php

namespace App\Http\Requests\Admin;

use App\Models\Advertisement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveAdvertisementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('advertisement')
            ? $this->user()->can('update', $this->route('advertisement'))
            : $this->user()->can('create', Advertisement::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'placement' => ['required', Rule::in(array_keys(Advertisement::PLACEMENTS))],
            'format' => ['required', Rule::in(array_keys(Advertisement::FORMATS))],
            'body_html' => ['required', 'string', 'max:20000'],
            'is_active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ];
    }

    public function messages(): array
    {
        return [
            'ends_at.after_or_equal' => 'The end date must be on or after the start date.',
        ];
    }
}
