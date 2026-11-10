<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMINISTRATOR = 'super_administrator';
    public const ROLE_ADMINISTRATOR = 'administrator';
    public const ROLE_MANAGING_EDITOR = 'managing_editor';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_NEWS_REPORTER = 'news_reporter';
    public const ROLE_MULTIMEDIA_EDITOR = 'multimedia_editor';
    public const ROLE_ADVERTISEMENT_MANAGER = 'advertisement_manager';
    public const ROLE_COMMENT_MODERATOR = 'comment_moderator';

    public const ROLE_LABELS = [
        self::ROLE_SUPER_ADMINISTRATOR => 'Super Administrator',
        self::ROLE_ADMINISTRATOR => 'Administrator',
        self::ROLE_MANAGING_EDITOR => 'Managing Editor',
        self::ROLE_EDITOR => 'Editor',
        self::ROLE_NEWS_REPORTER => 'News Reporter',
        self::ROLE_MULTIMEDIA_EDITOR => 'Multimedia Editor',
        self::ROLE_ADVERTISEMENT_MANAGER => 'Advertisement Manager',
        self::ROLE_COMMENT_MODERATOR => 'Comment Moderator',
    ];

    public const ADMINISTRATIVE_ROLES = [
        self::ROLE_SUPER_ADMINISTRATOR,
        self::ROLE_ADMINISTRATOR,
    ];

    public const DASHBOARD_ROLES = [
        self::ROLE_SUPER_ADMINISTRATOR,
        self::ROLE_ADMINISTRATOR,
        self::ROLE_MANAGING_EDITOR,
        self::ROLE_EDITOR,
        self::ROLE_MULTIMEDIA_EDITOR,
        self::ROLE_ADVERTISEMENT_MANAGER,
        self::ROLE_COMMENT_MODERATOR,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public static function roleOptionsFor(self $actor): array
    {
        if (! $actor->isAdministrativeUser()) {
            return [];
        }

        $roles = $actor->isSuperAdministrator()
            ? array_keys(self::ROLE_LABELS)
            : array_values(array_diff(array_keys(self::ROLE_LABELS), self::ADMINISTRATIVE_ROLES));

        return array_intersect_key(self::ROLE_LABELS, array_flip($roles));
    }

    public function isSuperAdministrator(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMINISTRATOR;
    }

    public function isAdministrativeUser(): bool
    {
        return in_array($this->role, self::ADMINISTRATIVE_ROLES, true);
    }

    public function canAccessDashboard(): bool
    {
        return in_array($this->role, self::DASHBOARD_ROLES, true);
    }

    public function isEditorialLead(): bool
    {
        return $this->isAdministrativeUser() || $this->role === self::ROLE_MANAGING_EDITOR;
    }

    public function roleLabel(): string
    {
        return self::ROLE_LABELS[$this->role] ?? $this->role;
    }
}
