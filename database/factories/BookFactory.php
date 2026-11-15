<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'isbn' => fake()->unique()->isbn13(),
            'publication_year' => fake()->year(max:2026),
            'page_count' => fake()->numberBetween(100, 1000),
            'description' => fake()->paragraph(),
            'stock' => fake()->numberBetween(0, 50)
        ];
    }
}
