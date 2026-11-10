<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    public function show(string $path): Response
    {
        $file = MediaFile::where('path', $path)->firstOrFail();

        return Storage::disk('public')->response($file->path, null, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
