<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookAuthor;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(15)->create();
        $authors = Author::factory(10)->create();
        $books = Book::factory(20)->create();

        foreach ($books as $book) {
            $randomAuthorIds = $authors->random(fake()->numberBetween(1, min(3, $authors->count())))
                                       ->pluck('id')
                                       ->values();
            $pivotData = [];
            foreach ($randomAuthorIds as $index => $authorId) {
                $pivotData[$authorId] = [
                    'author_order' => $index + 1
                ];
            }

            $book->authors()->syncWithoutDetaching($pivotData);
        }

        // $books->each(function (Book $book) use ($authors) {
        //     $randomAuthorIds = $authors->random(fake()->numberBetween(1, min(3, $authors->count())))
        //                        ->pluck('id')
        //                        ->toArray();

        //     $book->authors()->syncWithoutDetaching($randomAuthorIds);
        // });
        Loan::factory(10)->create();
    }
}
