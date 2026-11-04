<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'category_id',
        'published_at',
        'is_published',
        'featured_image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Automatically create slug from title
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    /**
     * Get the category that owns the post.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the tags for the post.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Scope for published posts only
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->where('published_at', '<=', now());
    }

    /**
     * Get the URL for the post
     */
    public function getUrlAttribute()
    {
        return route('blog.show', $this->slug);
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->published_at?->format('M d, Y');
    }

    /**
     * Get reading time (simple calculation)
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return max(1, ceil($wordCount / 200)) . ' min read';
    }

    /**
     * The featured image URL, or a local placeholder in the requested ratio
     * (the Blogy theme uses horizontal, square and vertical images).
     */
    public function imageUrl(string $ratio = 'horizontal'): string
    {
        if (filled($this->featured_image)) {
            return Str::startsWith($this->featured_image, ['http://', 'https://', '/'])
                ? $this->featured_image
                : Storage::disk('public')->url($this->featured_image);
        }

        $ratio = in_array($ratio, ['horizontal', 'square', 'vertical'], true) ? $ratio : 'horizontal';

        return asset("images/placeholders/post-{$ratio}.svg");
    }

    public function hasImage(): bool
    {
        return filled($this->featured_image);
    }
}
