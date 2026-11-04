<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, HasUniqueSlug;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    protected function slugSource(): string
    {
        return 'name';
    }

    /**
     * Get all posts with this tag
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * Get the tag URL
     */
    public function getUrlAttribute()
    {
        return route('blog.tag', $this->slug);
    }
}
