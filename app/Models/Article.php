<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_UNPUBLISHED = 'unpublished';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_ARCHIVED = 'archived';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_SUBMITTED,
        self::STATUS_APPROVED,
        self::STATUS_PUBLISHED,
        self::STATUS_SCHEDULED,
        self::STATUS_UNPUBLISHED,
        self::STATUS_REJECTED,
        self::STATUS_ARCHIVED,
    ];

    public const STATUS_LABELS = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_SUBMITTED => 'Submitted',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_PUBLISHED => 'Published',
        self::STATUS_SCHEDULED => 'Scheduled',
        self::STATUS_UNPUBLISHED => 'Unpublished',
        self::STATUS_REJECTED => 'Rejected',
        self::STATUS_ARCHIVED => 'Archived',
    ];

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'subheadline',
        'slug',
        'summary',
        'body',
        'featured_image_path',
        'status',
        'is_featured',
        'is_breaking',
        'is_sponsored',
        'scheduled_for',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_breaking' => 'boolean',
            'is_sponsored' => 'boolean',
            'scheduled_for' => 'datetime',
            'published_at' => 'datetime',
            'view_count' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function uniqueSlugFor(string $title): string
    {
        $base = Str::slug($title) ?: 'story';
        $slug = $base;
        $suffix = 2;

        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function availableActions(): array
    {
        return match ($this->status) {
            self::STATUS_DRAFT => [
                'publish' => 'Publish now',
                'schedule' => 'Schedule',
                'archive' => 'Archive',
            ],
            self::STATUS_SCHEDULED => [
                'publish' => 'Publish now',
                'schedule' => 'Reschedule',
                'cancel_schedule' => 'Cancel schedule',
            ],
            self::STATUS_PUBLISHED => [
                'unpublish' => 'Unpublish',
                'archive' => 'Archive',
            ],
            self::STATUS_UNPUBLISHED => [
                'publish' => 'Publish again',
                'archive' => 'Archive',
            ],
            self::STATUS_ARCHIVED => [
                'restore' => 'Restore to drafts',
            ],
            default => [],
        };
    }

    public function isPublic(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && ($this->published_at === null || $this->published_at->lte(now()));
    }

    public function readingMinutes(): int
    {
        $words = str_word_count(strip_tags((string) $this->body));

        return max(1, (int) ceil($words / 200));
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        $like = '%'.addcslashes($term, '%_\\').'%';

        return $query->where(function (Builder $query) use ($like) {
            $query->where('title', 'like', $like)
                ->orWhere('summary', 'like', $like)
                ->orWhere('body', 'like', $like);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->where(function (Builder $query) {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }
}
