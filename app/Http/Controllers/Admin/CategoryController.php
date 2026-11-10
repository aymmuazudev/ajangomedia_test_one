<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveCategoryRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::with('parent:id,name')
            ->withCount(['articles', 'children'])
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);

        return view('admin.categories.create', [
            'category' => new Category(['display_order' => 0]),
            'parents' => $this->parentOptions(),
        ]);
    }

    public function store(SaveCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $category = Category::create([
            ...$data,
            'slug' => Category::uniqueSlugFor($data['name']),
        ]);

        ActivityLog::record($request->user(), 'category.created', "Created the section {$category->name}.");

        return redirect()->route('admin.categories.index')
            ->with('status', "The section {$category->name} has been created.");
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        return view('admin.categories.edit', [
            'category' => $category,
            'parents' => $this->parentOptions($category),
        ]);
    }

    public function update(SaveCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['parent_id']) && $category->children()->exists()) {
            return back()->withInput()
                ->withErrors(['parent_id' => 'This section has sub-sections, so it must stay at the top level.']);
        }

        $category->update($data);

        ActivityLog::record($request->user(), 'category.updated', "Updated the section {$category->name}.");

        return redirect()->route('admin.categories.index')
            ->with('status', "The section {$category->name} has been updated.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        if ($category->children()->exists()) {
            return back()->withErrors(['category' => 'Move or delete the sub-sections of this section first.']);
        }

        if ($category->articles()->withTrashed()->exists()) {
            return back()->withErrors(['category' => 'This section still has stories. Move them to another section before deleting it.']);
        }

        $name = $category->name;
        $category->delete();

        ActivityLog::record(request()->user(), 'category.deleted', "Deleted the section {$name}.");

        return redirect()->route('admin.categories.index')->with('status', "The section {$name} has been deleted.");
    }

    private function parentOptions(?Category $exclude = null): Collection
    {
        return Category::query()
            ->whereNull('parent_id')
            ->when($exclude, fn ($query) => $query->whereKeyNot($exclude->getKey()))
            ->orderBy('display_order')
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
