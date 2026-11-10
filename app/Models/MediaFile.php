<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaFile extends Model
{
    protected $fillable = [
        'uploaded_by',
        'path',
        'original_name',
        'mime_type',
        'width',
        'height',
        'size',
        'alt_text',
    ];

    protected function casts(): array
    {
        return [
            'width' => 'integer',
            'height' => 'integer',
            'size' => 'integer',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function isInUse(): bool
    {
        return Article::withTrashed()->where('featured_image_path', $this->path)->exists();
    }
}
