<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'PHP', 'MySQL', 'HTML', 'CSS', 'JavaScript', 'Laravel', 'Vue.js', 'Full Stack'
        ]);
        
        $colors = [
            'PHP' => '#8892bf',
            'MySQL' => '#00758f',
            'HTML' => '#e34c26',
            'CSS' => '#264de4',
            'JavaScript' => '#f7df1e',
            'Laravel' => '#ff2d20',
            'Vue.js' => '#4fc08d',
            'Full Stack' => '#6366f1'
        ];

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 999), // Make slug unique
            'description' => fake()->sentence(),
            'color' => $colors[$name] ?? fake()->hexColor(),
        ];
    }
}