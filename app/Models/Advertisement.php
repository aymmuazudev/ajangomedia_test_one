<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Advertisement extends Model
{
    public const PLACEMENT_HOME_TOP = 'home_top';
    public const PLACEMENT_HOME_BOTTOM = 'home_bottom';
    public const PLACEMENT_SIDEBAR = 'sidebar';
    public const PLACEMENT_ARTICLE_INLINE = 'article_inline';

    public const PLACEMENTS = [
        self::PLACEMENT_HOME_TOP => 'Homepage, above the top stories',
        self::PLACEMENT_HOME_BOTTOM => 'Homepage, below the latest stories',
        self::PLACEMENT_SIDEBAR => 'Sidebar, beside the story page',
        self::PLACEMENT_ARTICLE_INLINE => 'Inside the story page, after the story',
    ];

    public const FORMATS = [
        'leaderboard' => 'Leaderboard (wide)',
        'square' => 'Square',
        'skyscraper' => 'Skyscraper (tall)',
        'banner' => 'Banner (short and wide)',
    ];

    protected $fillable = [
        'created_by',
        'name',
        'placement',
        'format',
        'body_html',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function placementLabel(): string
    {
        return self::PLACEMENTS[$this->placement] ?? $this->placement;
    }

    public function formatLabel(): string
    {
        return self::FORMATS[$this->format] ?? $this->format;
    }

    public function isRunning(): bool
    {
        return $this->is_active
            && ($this->starts_at === null || $this->starts_at->lte(now()))
            && ($this->ends_at === null || $this->ends_at->gte(now()));
    }

    public function scopeRunning(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }
}
