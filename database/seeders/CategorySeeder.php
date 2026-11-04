<?php

namespace Database\Seeders;

use App\Models\Category;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * The blog's categories, each created once with a clean slug ("php", "vuejs", ...) and its colour.
     */
    public function run(): void
    {
        foreach (array_keys(CategoryFactory::COLORS) as $name) {
            Category::factory()->named($name)->create();
        }
    }
}
