<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
        return [
            'title'             => ['required', 'string', 'max:255'],
            'slug'              => ['required', 'string', 'max:255', 'unique:books,slug'],
            'isbn'              => ['nullable', 'string', 'max:20', 'unique:books,isbn'],
            'publication_year'  => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'page_count'        => ['required', 'integer', 'min:0', 'max:32767'], // smallint check >= 0
            'description'       => ['nullable', 'string'],
            'stock'             => ['required', 'integer', 'min:0'],
            'available_stock'   => ['required', 'integer', 'min:0', 'lte:stock'],
            'author_ids'        => ['nullable', 'array'],
            'author_ids.*'      => ['integer', 'exists:authors,id'],
        ];
    }
}
