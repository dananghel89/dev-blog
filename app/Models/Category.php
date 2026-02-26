<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color'
    ];

    /**
     * Automatically create slug from name
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get all posts in this category
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get only published posts in this category
     */
    public function publishedPosts()
    {
        return $this->hasMany(Post::class)->published();
    }

    /**
     * Get the category URL
     */
    public function getUrlAttribute()
    {
        return route('blog.category', $this->slug);
    }

    /**
     * Get post count
     */
    public function getPostCountAttribute()
    {
        return $this->publishedPosts()->count();
    }
}
