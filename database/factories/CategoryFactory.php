<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * The blog's categories and their colours (also used by CategorySeeder).
     */
    public const COLORS = [
        'PHP' => '#8892bf',
        'MySQL' => '#00758f',
        'HTML' => '#e34c26',
        'CSS' => '#264de4',
        'JavaScript' => '#f7df1e',
        'Laravel' => '#ff2d20',
        'Vue.js' => '#4fc08d',
        'Full Stack' => '#6366f1',
    ];

    public function definition(): array
    {
        // Random name with a unique slug suffix, so tests can create any number of categories.
        // CategorySeeder uses named() for the real, clean categories.
        $name = fake()->randomElement(array_keys(self::COLORS));

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 999), // Make slug unique
            'description' => fake()->sentence(),
            'color' => self::COLORS[$name],
        ];
    }

    /**
     * A category with this exact name, a clean slug ("Vue.js" -> "vuejs") and its colour.
     */
    public function named(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
            'slug' => Str::slug($name),
            'color' => self::COLORS[$name] ?? fake()->hexColor(),
        ]);
    }
}
