<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const NAVIGATION = [
        'Nigeria',
        'Bauchi',
        'Politics',
        'Business',
        'Africa',
        'World',
        'Education',
        'Health',
        'Technology',
        'Security',
        'Sports',
        'Entertainment',
        'Opinion',
        'Features',
        'Investigations',
        'Videos',
    ];

    public function up(): void
    {
        $now = now();

        $rows = collect(self::NAVIGATION)->map(fn (string $name, int $index) => [
            'name' => $name,
            'slug' => Str::slug($name),
            'parent_id' => null,
            'display_order' => ($index + 1) * 10,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        DB::table('categories')->insertOrIgnore($rows);
    }

    public function down(): void
    {
        DB::table('categories')
            ->whereIn('slug', array_map(fn (string $name) => Str::slug($name), self::NAVIGATION))
            ->whereNull('parent_id')
            ->whereNotExists(fn ($query) => $query->select(DB::raw(1))
                ->from('articles')
                ->whereColumn('articles.category_id', 'categories.id'))
            ->delete();
    }
};
