<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadMediaRequest;
use App\Models\ActivityLog;
use App\Models\MediaFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    private const PER_PAGE = 24;
    private const DISK = 'public';

    public function index(): View
    {
        $this->authorize('viewAny', MediaFile::class);

        $files = MediaFile::with('uploader:id,name')->latest()->paginate(self::PER_PAGE);

        return view('admin.media.index', compact('files'));
    }

    public function store(UploadMediaRequest $request): RedirectResponse
    {
        $altText = $request->validated('alt_text');
        $uploaded = 0;

        foreach ($request->file('files') as $file) {
            $path = $file->store('media/'.now()->format('Y/m'), self::DISK);
            [$width, $height] = getimagesize($file->getRealPath()) ?: [null, null];

            MediaFile::create([
                'uploaded_by' => $request->user()->id,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'width' => $width,
                'height' => $height,
                'size' => $file->getSize(),
                'alt_text' => $altText,
            ]);

            $uploaded++;
        }

        ActivityLog::record($request->user(), 'media.uploaded', "Uploaded {$uploaded} image(s) to the media library.");

        return back()->with('status', $uploaded === 1 ? 'The image has been uploaded.' : "{$uploaded} images have been uploaded.");
    }

    public function destroy(Request $request, MediaFile $mediaFile): RedirectResponse
    {
        $this->authorize('delete', $mediaFile);

        if ($mediaFile->isInUse()) {
            return back()->withErrors(['media' => 'This image is the featured image of a story. Choose another image for that story first.']);
        }

        Storage::disk(self::DISK)->delete($mediaFile->path);
        $mediaFile->delete();

        ActivityLog::record($request->user(), 'media.deleted', "Deleted the image {$mediaFile->original_name}.");

        return back()->with('status', 'The image has been deleted.');
    }
}
