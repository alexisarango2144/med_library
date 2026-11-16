<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'slug',
        'isbn',
        'publication_year',
        'page_count',
        'description',
        'stock',
        'available_stock'
    ];

    protected function casts(): array
    {
        return [
            'publication_year'  => 'integer',
            'page_count'        => 'integer',
            'stock'             => 'integer',
            'available_stock'   => 'integer',
        ];
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_author', 'book_id', 'author_id')
            ->using(BookAuthor::class)
            ->withPivot('author_order')
            ->orderByPivot('author_order', 'asc');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => trim($value),
        );
    }

    protected function isbn(): Attribute
    {
        return Attribute::make(
            set: fn(?string $value) => $value ? preg_replace('/[^0-9X]/i', '', $value) : null,
        );
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function syncAuthorsWithOrder(array $authors): void
    {
        $pivotData = [];

        foreach(array_values($authors) as $index => $author) {
            if(is_array($author)) {
                $pivotData[$author['id']] = [
                    'author_order' => $author['author_order'] ?? ($index + 1),
                ];
            } else {
                $pivotData[$author] = [
                    'author_order' => $index + 1,
                ];
            }
        }

        $this->authors()->sync($pivotData);
    }

    /*
    | Query Scopes
    */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeAvailableStock(Builder $query): Builder
    {
        return $query->where('available_stock', '>', 0);
    }

    public function scopeByPublicationYear(Builder $query, int $year): Builder
    {
        return $query->where('publication_year', $year);
    }

    public function scopeByAuthor(Builder $query, int|string $author): Builder
    {
        return $query->whereHas('authors', function (Builder $q) use ($author) {
            if (is_numeric($author)) {
                $q->where('authors.id', $author);
            } else {
                $search = addcslashes(trim($author), '%_\\');
                $q->whereRaw(
                    "(COALESCE(authors.name, '') || ' ' || COALESCE(authors.last_name, '') || ' ' || COALESCE(authors.nationality, '')) ILIKE ?",
                    ["%{$search}%"]
                );
            }
        });
    }
}
