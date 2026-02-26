<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $tags = [
            'debugging', 'tutorial', 'performance', 'security', 
            'optimization', 'beginner', 'advanced', 'tips',
            'laravel', 'eloquent', 'blade', 'api', 'database',
            'frontend', 'backend', 'responsive', 'animation'
        ];
        
        $name = fake()->unique()->randomElement($tags);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
