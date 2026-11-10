<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $category = $this->route('category');

        return $category instanceof Category
            ? $this->user()->can('update', $category)
            : $this->user()->can('create', Category::class);
    }

    public function rules(): array
    {
        $category = $this->route('category');

        $parentRules = [
            'nullable',
            'integer',
            Rule::exists('categories', 'id')->whereNull('parent_id'),
        ];

        if ($category instanceof Category) {
            $parentRules[] = Rule::notIn([$category->id]);
        }

        return [
            'name' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:500'],
            'parent_id' => $parentRules,
            'display_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ];
    }
}
