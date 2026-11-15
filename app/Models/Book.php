<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'isbn',
        'publication_year',
        'page_count',
        'description',
        'stock'
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'page_count'       => 'integer',
            'stock'            => 'integer',
        ];
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_author', 'book_id', 'author_id');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => trim($value),
        );
    }

    protected function isbn(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? preg_replace('/[^0-9X]/i', '', $value) : null,
        );
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
