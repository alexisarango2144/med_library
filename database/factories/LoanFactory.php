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
        // 1. Generamos borrowed_at como punto de partida
        $borrowedAt = \Carbon\Carbon::instance(fake()->dateTimeBetween('-2 months', '-1 day'));

        // 2. estimated_return_date garantizada después del préstamo (ej. 7 a 21 días)
        $estimatedReturnDate = $borrowedAt->copy()->addDays(fake()->numberBetween(7, 21))->toDateString();

        $status = fake()->randomElement(['active', 'returned', 'overdue', 'lost']);

        // 3. return_date solo si fue devuelto, garantizando que sea >= borrowed_at
        $returnDate = null;
        if ($status === 'returned') {
            $returnDate = $borrowedAt->copy()->addDays(fake()->numberBetween(1, 15))->toDateString();
        }

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
