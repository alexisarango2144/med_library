<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookAuthor extends Pivot
{

    protected $table = "book_author";

    public $incrementing = false;

    public $timestamps = false;
    protected $fillable = [
        'book_id',
        'author_id',
        'author_order'
    ];

    protected function casts(): array
    {
        return [
            'book_id' => 'integer',
            'author_id' => 'integer',
            'author_order' => 'integer'
        ];
    }
}
