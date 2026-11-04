<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Generates a unique slug from slugSource() when a model is created without one:
 * "same-title", then "same-title-2", "same-title-3", ... An explicit slug is kept as is.
 */
trait HasUniqueSlug
{
    /**
     * The attribute the slug is built from (e.g. "title" or "name").
     */
    abstract protected function slugSource(): string;

    protected static function bootHasUniqueSlug(): void
    {
        static::creating(function ($model) {
            if (blank($model->slug)) {
                $model->slug = $model->uniqueSlug(Str::slug((string) $model->{$model->slugSource()}));
            }
        });
    }

    protected function uniqueSlug(string $base): string
    {
        $base = $base !== '' ? $base : Str::lower(class_basename($this));
        $slug = $base;

        for ($i = 2; static::query()->where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }
}
