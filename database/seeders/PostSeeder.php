<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * One unique title per seeded post.
     */
    private const TITLES = [
        'How I Fixed a Memory Leak in Laravel Queues',
        'Why This MySQL Query Brought Down Production',
        'The CSS Grid Bug That Took 3 Hours to Fix',
        'Debugging PHP Session Issues: A Horror Story',
        '10 JavaScript Performance Tips You Need to Know',
        'Understanding MySQL Indexes the Hard Way',
        'When HTML5 Forms Attack: A Validation Nightmare',
        'The Day Composer Dependencies Broke Everything',
        'Fixing CORS Issues in PHP APIs',
        'CSS Flexbox vs Grid: Real World Comparison',
        'Eager Loading Saved Our Dashboard',
        'A Race Condition in My Checkout Flow',
        'Timezones Will Get You Eventually',
        'Migrating a Legacy App to Laravel 12',
        'The N+1 Query I Kept Missing',
        'Building Accessible Forms Without a Framework',
        'Why My Vue Component Re-rendered 400 Times',
        'Taming Sticky Headers in Mobile Safari',
        'Deadlocks in MySQL: What Actually Happened',
        'From jQuery to Vanilla JavaScript in a Weekend',
        'Caching Strategies That Actually Helped',
        'The Off-by-One Error in My Pagination',
        'Writing Tests for Code You Did Not Write',
        'UTF-8 Everywhere: Lessons From a Broken Import',
        'Debouncing Search Inputs the Right Way',
        'How I Shrunk Our CSS Bundle by 60%',
        'Queues, Retries and the Job That Ran Twice',
        'Full-Text Search in MySQL Before Reaching for Elastic',
        'The Hidden Cost of Inline Styles',
        'Lazy Loading Images Without Layout Shift',
        'Refactoring a 2,000-Line Controller',
        'What I Learned Reading the Laravel Source',
        'Rate Limiting a Public Contact Form',
        'Semantic HTML That Screen Readers Understand',
        'Choosing Between Livewire and Vue',
        'Handling File Uploads Safely in PHP',
        'Floating Point Money Bugs and How to Avoid Them',
        'A Practical Guide to Database Transactions',
        'Making Bootstrap Look Less Like Bootstrap',
        'Shipping a Side Project in 30 Days',
    ];

    /**
     * 40 published posts with unique titles, spread evenly over the seeded categories
     * (5 each), each with 2-4 tags.
     */
    public function run(): void
    {
        $categories = Category::orderBy('id')->get();
        $tags = Tag::all();

        Post::factory()
            ->count(count(self::TITLES))
            ->published()
            ->state(new Sequence(fn (Sequence $sequence) => [
                'title' => self::TITLES[$sequence->index],
                'slug' => null, // generated from the title (HasUniqueSlug)
                'category_id' => $categories[$sequence->index % $categories->count()]->id,
            ]))
            ->create()
            ->each(fn (Post $post) => $post->tags()->attach(
                $tags->random(rand(2, 4))->pluck('id')->all()
            ));
    }
}
