<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use Illuminate\Support\Facades\Route;

Route::apiResource('books', BookController::class);

Route::apiResource('authors', AuthorController::class);

Route::put('loans/{loan}/return', [LoanController::class, 'returnBook'])
    ->name('loans.return');

Route::apiResource('loans', LoanController::class)
    ->except(['destroy']);