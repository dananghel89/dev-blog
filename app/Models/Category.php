<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasUniqueSlug;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color'
    ];

    protected function slugSource(): string
    {
        return 'name';
    }

    /**
     * Get all posts in this category
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }


    /**
     * Get the category URL
     */
    public function getUrlAttribute()
    {
        return route('blog.category', $this->slug);
    }

}
