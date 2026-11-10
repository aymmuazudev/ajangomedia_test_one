<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveArticleRequest;
use App\Models\ActivityLog;
use App\Models\Article;
use App\Models\Category;
use App\Models\MediaFile;
use App\Models\Tag;
use App\Services\ArticlePublisher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    private const PER_PAGE = 20;
    private const PICKER_LIMIT = 24;

    private const INTENT_MESSAGES = [
        'save' => 'Your changes have been saved.',
        'publish' => 'The story is now published.',
        'schedule' => 'The story is scheduled for publication.',
        'unpublish' => 'The story has been unpublished.',
        'archive' => 'The story has been archived.',
        'restore' => 'The story has been restored to drafts.',
        'cancel_schedule' => 'The schedule has been cancelled. The story is back in drafts.',
    ];

    public function __construct(private ArticlePublisher $publisher)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Article::class);

        $status = (string) $request->query('status', '');
        $categoryId = (int) $request->query('category', 0);
        $search = trim((string) $request->query('q', ''));

        $articles = Article::query()
            ->with(['category:id,name', 'author:id,name'])
            ->when(in_array($status, Article::STATUSES, true), fn ($query) => $query->where('status', $status))
            ->when($categoryId > 0, fn ($query) => $query->where('category_id', $categoryId))
            ->when($search !== '', fn ($query) => $query->where('title', 'like', '%'.addcslashes($search, '%_\\').'%'))
            ->latest('updated_at')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.articles.index', [
            'articles' => $articles,
            'categories' => $this->categoryOptions(),
            'statuses' => Article::STATUS_LABELS,
            'filters' => compact('status', 'categoryId', 'search'),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Article::class);

        return view('admin.articles.create', [
            'article' => new Article(['status' => Article::STATUS_DRAFT]),
            'categories' => $this->categoryOptions(),
            'mediaFiles' => $this->pickerFiles(null),
            'tagsText' => '',
        ]);
    }

    public function store(SaveArticleRequest $request): RedirectResponse
    {
        $article = new Article([
            'author_id' => $request->user()->id,
            'status' => Article::STATUS_DRAFT,
        ]);
        $article->slug = Article::uniqueSlugFor($request->validated('title'));

        $this->persist($article, $request);

        ActivityLog::record($request->user(), 'article.created', "Created the story \"{$article->title}\".");

        return redirect()->route('admin.articles.edit', $article)
            ->with('status', self::INTENT_MESSAGES[$request->validated('intent')]);
    }

    public function edit(Article $article): View
    {
        $this->authorize('update', $article);

        $article->load(['category:id,name', 'tags:id,name']);

        return view('admin.articles.edit', [
            'article' => $article,
            'categories' => $this->categoryOptions(),
            'mediaFiles' => $this->pickerFiles($article->featured_image_path),
            'tagsText' => $article->tags->pluck('name')->implode(', '),
        ]);
    }

    public function update(SaveArticleRequest $request, Article $article): RedirectResponse
    {
        $this->persist($article, $request);

        ActivityLog::record(
            $request->user(),
            'article.'.$request->validated('intent'),
            "Updated the story \"{$article->title}\" ({$article->statusLabel()}).",
        );

        return redirect()->route('admin.articles.edit', $article)
            ->with('status', self::INTENT_MESSAGES[$request->validated('intent')]);
    }

    public function destroy(Article $article): RedirectResponse
    {
        $this->authorize('delete', $article);

        $article->delete();

        ActivityLog::record(request()->user(), 'article.deleted', "Deleted the story \"{$article->title}\".");

        return redirect()->route('admin.articles.index')->with('status', 'The story has been deleted.');
    }

    private function persist(Article $article, SaveArticleRequest $request): void
    {
        $intent = $request->validated('intent');

        DB::transaction(function () use ($article, $request, $intent) {
            $article->fill([
                'category_id' => $request->validated('category_id'),
                'title' => $request->validated('title'),
                'subheadline' => $request->validated('subheadline'),
                'summary' => $request->validated('summary'),
                'body' => $request->validated('body'),
                'featured_image_path' => $request->validated('featured_image_path'),
                'is_featured' => $request->boolean('is_featured'),
                'is_breaking' => $request->boolean('is_breaking'),
                'is_sponsored' => $request->boolean('is_sponsored'),
            ]);

            $this->publisher->apply($article, $intent, $request->scheduledFor());

            $article->tags()->sync($this->tagIds($request->tagNames()));
        });
    }

    private function tagIds(array $names): array
    {
        return collect($names)
            ->map(fn (string $name) => Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name])->id)
            ->all();
    }

    private function categoryOptions()
    {
        return Category::with('parent:id,name')
            ->orderBy('display_order')
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id']);
    }

    private function pickerFiles(?string $currentPath)
    {
        $files = MediaFile::query()
            ->latest()
            ->limit(self::PICKER_LIMIT)
            ->get();

        if ($currentPath !== null && ! $files->contains('path', $currentPath)) {
            $current = MediaFile::where('path', $currentPath)->first();

            if ($current) {
                $files->prepend($current);
            }
        }

        return $files;
    }
}
