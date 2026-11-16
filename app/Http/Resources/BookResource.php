<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'slug'             => $this->slug,
            'isbn'             => $this->isbn,
            'publication_year' => $this->publication_year,
            'page_count'       => $this->page_count,
            'description'      => $this->description,
            'stock'            => $this->stock,
            'available_stock'  => $this->available_stock,
            'is_available'     => $this->available_stock > 0,
            'deleted_at'       => $this->whenNotNull($this->deleted_at?->toISOString()),
            'loans_count'      => $this->whenCounted('loans'),
            'authors'          => AuthorResource::collection($this->whenLoaded('authors')),
        ];
    }
}
