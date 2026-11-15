<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLoanRequest extends FormRequest
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
        $loan = $this->route('loan');

        $borrowedAt = $this->input('borrowed_at', $loan->borrowed_at->toDateString());
        
        return [
            'user_id' =>                ['sometimes', 'required', 'integer', 'exists:users,id'],
            'book_id' =>                ['sometimes', 'required', 'integer', 'exists:books,id'],
            'borrowed_at' =>            ['sometimes', 'required', 'date'],
            'estimated_return_date' =>  ['sometines', 'required', 'date', 'after_or_equal:{$borrowed_at}'],
            'return_date' =>            ['nullable', 'date', 'after_or_equal:{$borrowed_at}'],
            'status'=>                  ['sometimes', 'required', 'string', Rule::in(['active','returned','overdue','lost'])],
        ];
    }
}
