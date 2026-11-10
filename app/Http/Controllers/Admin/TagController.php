<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    private const PER_PAGE = 30;

    public function index(): View
    {
        $this->authorize('viewAny', Tag::class);

        $tags = Tag::withCount('articles')->orderBy('name')->paginate(self::PER_PAGE);

        return view('admin.tags.index', compact('tags'));
    }

    public function edit(Tag $tag): View
    {
        $this->authorize('update', $tag);

        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $this->authorize('update', $tag);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
        ]);

        $tag->update($data);

        ActivityLog::record($request->user(), 'tag.updated', "Renamed the tag to {$tag->name}.");

        return redirect()->route('admin.tags.index')->with('status', "The tag {$tag->name} has been updated.");
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $this->authorize('delete', $tag);

        $name = $tag->name;
        $tag->delete();

        ActivityLog::record(request()->user(), 'tag.deleted', "Deleted the tag {$name}.");

        return redirect()->route('admin.tags.index')->with('status', "The tag {$name} has been deleted.");
    }
}
