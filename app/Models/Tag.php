<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug'
    ];

    /**
     * Automatically create slug from name
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Get all posts with this tag
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * Get only published posts with this tag
     */
    public function publishedPosts()
    {
        return $this->belongsToMany(Post::class)->published();
    }

    /**
     * Get the tag URL
     */
    public function getUrlAttribute()
    {
        return route('blog.tag', $this->slug);
    }

    /**
     * Get post count
     */
    public function getPostCountAttribute()
    {
        return $this->publishedPosts()->count();
    }
}
