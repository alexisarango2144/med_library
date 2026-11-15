<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loans';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'estimated_return_date',
        'return_date',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'book_id' => 'integer',
            'user_id' => 'integer',
            'borrowed_at' => 'datetime',
            'estimated_return_date' => 'datetime',
            'return_date' => 'datetime',
            'status' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}