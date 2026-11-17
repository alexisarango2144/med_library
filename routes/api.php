<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

/**
 * Rutas Públicas
 */
Route::apiResource('books', BookController::class)->only(['index', 'show']);
Route::apiResource('authors', AuthorController::class)->only(['index', 'show']);


/**
 * Rutas protegidas por Sanctum
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('books', BookController::class)->except(['index', 'show']);
    Route::apiResource('authors', AuthorController::class)->except(['index', 'show']);

    Route::apiResource('loans', LoanController::class)->except(['destroy']);
    Route::put('loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');
});




