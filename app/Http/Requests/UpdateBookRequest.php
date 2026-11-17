<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $book = $this->route('book');
        $bookId = $book->id ?? $book;
        
        $maxStock = $this->input('stock', $book->stock ?? 0);

        return [
            'title'             => ['sometimes', 'required', 'string', 'max:255'],
            'slug'              => ['sometimes', 'required', 'string', 'max:255', Rule::unique('books', 'slug')->ignore($bookId)],
            'isbn'              => ['nullable', 'string', 'max:20', Rule::unique('books', 'isbn')->ignore($bookId)],
            'publication_year'  => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'page_count'        => ['sometimes', 'required', 'integer', 'min:0', 'max:32767'],
            'description'       => ['nullable', 'string'],
            'stock'             => ['sometimes', 'required', 'integer', 'min:0'],
            'available_stock'   => ['sometimes', 'required', 'integer', 'min:0', 'max:' . $maxStock],
            'author_ids'        => ['sometimes', 'nullable', 'array'],
            'author_ids.*'      => ['integer', 'exists:authors,id'],
        ];
    }
}
