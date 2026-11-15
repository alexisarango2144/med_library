<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'last_name',
        'birthdate',
        'nationality',
        'biography',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
        ];
    }
    
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_author', 'author_id', 'book_id');
    }

    /**
     * Mutator/Accessor: Normaliza el nombre al asignarlo
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => trim($value),
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim("{$this->name} {$this->last_name}"),
        );
    }
}
