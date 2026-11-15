<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'borrowed_at'           => $this->borrowed_at?->toISOString(),
            'estimated_return_date' => $this->estimated_return_date?->format('Y-m-d'),
            'return_date'           => $this->return_date?->format('Y-m-d'),
            'status'                => $this->status,
            'is_delayed'            => $this->is_delayed,
            'user'                  => $this->whenLoaded('user', function () {
                return [
                    'id'        => $this->user->id,
                    'full_name' => trim("{$this->user->name} {$this->user->last_name}"),
                    'email'     => $this->user->email,
                ];
            }),
            'book'                  => $this->whenLoaded('book', function () {
                return [
                    'id'    => $this->book->id,
                    'title' => $this->book->title,
                    'slug'  => $this->book->slug,
                    'isbn'  => $this->book->isbn,
                ];
            }),
        ];
    }
}
