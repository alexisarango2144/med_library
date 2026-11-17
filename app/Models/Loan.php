<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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

    protected function status(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtolower(trim($value)),
        );
    }

    protected function isDelayed(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status === 'returned') {
                    return $this->return_date && $this->return_date->gt($this->estimated_return_date);
                }

                return Carbon::today()->gt($this->estimated_return_date);
            },
        );
    }

    public function markAsReturned(): void
    {
        DB::transaction(function () {
            $this->update([
                'status'      => 'returned',
                'return_date' => now()->toDateString(),
            ]);

            $this->book()->increment('available_stock');
        });
    }
}