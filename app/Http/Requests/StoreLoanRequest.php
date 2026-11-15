<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'book_id' => [
                'required',
                'integer',
                Rule::exists('books', 'id')->where(function ($query) {
                    $query->where('stock', '>', 0);
                }),
            ],
            'borrowed_at'           => ['nullable', 'date'],
            'estimated_return_date' => ['required', 'date', 'after_or_equal:borrowed_at'],
            'return_date'           => ['nullable', 'date', 'after_or_equal:borrowed_at'],
            'status'                => ['nullable', 'string', Rule::in(['active', 'returned', 'overdue', 'lost'])],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'El campo usuario es obligatorio.',
            'user_id.integer' => 'El campo usuario debe ser un número entero.',
            'user_id.exists' => 'El usuario seleccionado no existe.',

            'book_id.required' => 'El campo libro es obligatorio.',
            'book_id.integer' => 'El campo libro debe ser un número entero.',
            'book_id.exists' => 'El libro seleccionado no existe o no tiene stock disponible.',

            'borrowed_at.date' => 'El campo fecha de préstamo debe ser una fecha válida.',

            'estimated_return_date.required' => 'El campo fecha estimada de devolución es obligatorio.',
            'estimated_return_date.date' => 'El campo fecha estimada de devolución debe ser una fecha válida.',
            'estimated_return_date.after_or_equal' => 'La fecha estimada de devolución debe ser igual o posterior a la fecha de préstamo.',

            'return_date.date' => 'El campo fecha de devolución debe ser una fecha válida.',
            'return_date.after_or_equal' => 'La fecha de devolución debe ser igual o posterior a la fecha de préstamo.',

            'status.string' => 'El campo estado debe ser una cadena de texto.',
            'status.in' => "El estado seleccionado no es válido. Los valores permitidos son: active, returned, overdue, lost.",
        ];
    }

    protected function prepareForValidation(): void
    {
        if(!$this->has('borrowed_at')) {
            $this->merge([
                'borrowed_at' => now()->toDateString(),
            ]);
        }

        if(!$this->has('status')) {
            $this->merge([
                'status' => 'active',
            ]);
        }
    }
}
