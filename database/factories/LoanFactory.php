<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['active', 'returned', 'overdue', 'lost']);

        $borrowedAt = ($status === 'overdue')
            ? \Carbon\Carbon::instance(fake()->dateTimeBetween('-2 months', '-22 days'))
            : \Carbon\Carbon::instance(fake()->dateTimeBetween('-14 days', '-1 day'));

        $estimatedReturnDate = $borrowedAt->copy()->addDays(fake()->numberBetween(7, 15))->toDateString();
        
        $returnDate = ($status === 'returned')
            ? $borrowedAt->copy()->addDays(fake()->numberBetween(1, 14))->toDateString()
            : null;

        return [
            'user_id'               => User::inRandomOrder()->value('id') ?? User::factory(),
            'book_id'               => Book::inRandomOrder()->value('id') ?? Book::factory(),
            'borrowed_at'           => $borrowedAt,
            'estimated_return_date' => $estimatedReturnDate,
            'return_date'           => $returnDate,
            'status'                => $status,
        ];
    }
}
