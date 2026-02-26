<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Category;
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
            'CSS Flexbox vs Grid: Real World Comparison'
        ]);

        $uniqueNumber = fake()->unique()->numberBetween(1, 9999);

        $content = $this->generatePostContent();
        $excerpt = Str::limit(strip_tags($content), 150);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $uniqueNumber,
            'content' => $content,
            'excerpt' => $excerpt,
            'featured_image' => $this->generatePlaceholdCoImage(),
            'category_id' => Category::factory(),
            'is_published' => fake()->boolean(80),
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    private function generatePlaceholdCoImage(): string
    {
        $width = 1200;
        $height = 630;
        
        // Random background color (hex without #)
        $bgColor = str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        
        // Random text color (usually white or black)
        $textColor = mt_rand(0, 1) ? 'ffffff' : '000000';
        
        // Random text
        $texts = ['PHP', 'MySQL', 'Laravel', 'JavaScript', 'CSS', 'HTML', 'Coding', 'Debugging', 'DevOps', 'Server'];
        $text = fake()->randomElement($texts);
        
        // 30% chance to return null (no image)
        if (fake()->boolean(30)) {
            return '';
        }
        
        return "https://placehold.co/{$width}x{$height}/{$bgColor}/{$textColor}?text=" . urlencode($text);
    }

    private function generatePostContent(): string
    {
        $sections = [];

        // Problem section
        $sections[] = "<h2>The Problem</h2>";
        $sections[] = "<p>" . fake()->paragraphs(3, true) . "</p>";
        
        // Debugging section
        $sections[] = "<h2>Initial Debugging</h2>";
        $sections[] = "<p>" . fake()->paragraphs(2, true) . "</p>";
        
        // Code example
        $sections[] = "<pre><code class=\"language-php\">" . htmlspecialchars('<?php

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
}') . "</code></pre>";
        
        // Solution section
        $sections[] = "<h2>The Solution</h2>";
        $sections[] = "<p>" . fake()->paragraphs(2, true) . "</p>";
        
        // Lessons learned
        $sections[] = "<h2>Lessons Learned</h2>";
        $sections[] = "<ul>";
        for ($i = 0; $i < 3; $i++) {
            $sections[] = "<li>" . fake()->sentence() . "</li>";
        }
        $sections[] = "</ul>";

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
