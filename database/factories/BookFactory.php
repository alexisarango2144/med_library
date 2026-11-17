<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = rtrim(fake()->unique()->sentence(4), '.');
        $stock = fake()->numberBetween(1,50);
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'isbn' => fake()->unique()->isbn13(),
            'publication_year' => fake()->year('now'),
            'page_count' => fake()->numberBetween(100, 1000),
            'description' => fake()->paragraph(),
            'stock' => $stock,
            'available_stock' => fake()->numberBetween(0, $stock)
        ];
    }
}
