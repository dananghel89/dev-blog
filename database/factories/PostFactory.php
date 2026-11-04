<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->randomElement([
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
        ]);

        $uniqueNumber = fake()->unique()->numberBetween(1, 9999);

        $content = $this->generatePostContent();
        $excerpt = Str::limit(strip_tags($content), 150);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.$uniqueNumber,
            'content' => $content,
            'excerpt' => $excerpt,
            // No external placeholder service: views fall back to the local SVGs (Post::imageUrl()).
            'featured_image' => null,
            'category_id' => Category::factory(),
            'is_published' => fake()->boolean(80),
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    private function generatePostContent(): string
    {
        $sections = [];

        // Problem section
        $sections[] = '<h2>The Problem</h2>';
        $sections[] = '<p>'.fake()->paragraphs(3, true).'</p>';

        // Debugging section
        $sections[] = '<h2>Initial Debugging</h2>';
        $sections[] = '<p>'.fake()->paragraphs(2, true).'</p>';

        // Code example
        $sections[] = '<pre><code class="language-php">'.htmlspecialchars('<?php

// This is where the bug was hiding
public function processQueue()
{
    $items = Item::all(); // Memory intensive!
    
    foreach ($items as $item) {
        // Process each item
        $this->process($item);
    }
}

// The fix - use chunking
public function processQueueFixed()
{
    Item::chunk(100, function ($items) {
        foreach ($items as $item) {
            $this->process($item);
        }
    });
}').'</code></pre>';

        // Solution section
        $sections[] = '<h2>The Solution</h2>';
        $sections[] = '<p>'.fake()->paragraphs(2, true).'</p>';

        // Lessons learned
        $sections[] = '<h2>Lessons Learned</h2>';
        $sections[] = '<ul>';
        for ($i = 0; $i < 3; $i++) {
            $sections[] = '<li>'.fake()->sentence().'</li>';
        }
        $sections[] = '</ul>';

        return implode("\n\n", $sections);
    }

    // State methods
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * Published, but with a publish date in the future: not visible until then.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('+1 day', '+30 days'),
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function withCategory($categoryId): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $categoryId,
        ]);
    }
}
