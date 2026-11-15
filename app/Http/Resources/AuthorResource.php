<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>          $this->id,
            'name'=> $this->name,
            'last_name'=> $this->last_name,
            'full_name' => $this->full_name,
            'bithdate'=> $this->birthdate?->format('Y-m-d'),
            'nationality'=> $this->nationality,
            'author_order' => $this->whenPivotLoaded('book_author', function () {
                return $this->pivot->author_order;
            }),
            'books_count' => $this->whenCounted('books'),
            'books' => $this->whenLoaded('books', function () {
                return $this->books->map(fn ($book)=> [
                    'id'=> $book->id,
                    'title' => $book->title,
                    'slug' => $book->slug,
                    'isbn' => $book->isbn,
                    'publication_year' => $book->publication_year,
                    'available_stock' => $book->available_stock,
                    'author_order ' => $book->pivot?->author_order,
                ]);
            }),
        ];
    }
}
