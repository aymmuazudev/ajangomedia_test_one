<?php

namespace App\Http\Requests\Admin;

use App\Models\Article;
use App\Services\ArticlePublisher;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SaveArticleRequest extends FormRequest
{
    private const MAX_TAGS = 15;
    private const MAX_TAG_LENGTH = 60;

    public function authorize(): bool
    {
        $article = $this->route('article');

        return $article instanceof Article
            ? $this->user()->can('update', $article)
            : $this->user()->can('create', Article::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'subheadline' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:200000'],
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'featured_image_path' => ['nullable', 'string', 'max:255', Rule::exists('media_files', 'path')],
            'tags' => ['nullable', 'string', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_breaking' => ['nullable', 'boolean'],
            'is_sponsored' => ['nullable', 'boolean'],
            'scheduled_for' => ['nullable', 'date_format:Y-m-d\TH:i'],
            'editor_note' => ['nullable', 'required_if:intent,reject', 'string', 'max:2000'],
            'intent' => ['required', Rule::in(ArticlePublisher::INTENTS)],
        ];
    }

    public function messages(): array
    {
        return [
            'editor_note.required_if' => 'Add a note explaining what the reporter needs to change.',
        ];
    }

    public function tagNames(): array
    {
        return collect(explode(',', (string) $this->input('tags')))
            ->map(fn (string $name) => trim($name))
            ->filter(fn (string $name) => Str::slug($name) !== '')
            ->unique(fn (string $name) => Str::slug($name))
            ->take(self::MAX_TAGS)
            ->map(fn (string $name) => mb_substr($name, 0, self::MAX_TAG_LENGTH))
            ->values()
            ->all();
    }

    public function scheduledFor(): ?Carbon
    {
        $value = $this->input('scheduled_for');

        return $value ? Carbon::parse($value, config('app.timezone')) : null;
    }
}
