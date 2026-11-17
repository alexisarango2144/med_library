<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

/**
 * Rutas Públicas
 */
Route::apiResource('books', BookController::class)->only(['index', 'show']);
Route::apiResource('authors', AuthorController::class)->only(['index', 'show']);


/**
 * Rutas protegidas por Sanctum
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('books', BookController::class)->except(['index', 'show']);
    Route::apiResource('authors', AuthorController::class)->except(['index', 'show']);

    Route::apiResource('loans', LoanController::class)->except(['destroy']);
    Route::put('loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');

    /**
     * Ruta con lógica incluída, pendiente generar Controller
     */
    Route::get('/users', function (Request $request) {
        $users = User::query()
            ->select(['id', 'name', 'email'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = addcslashes(trim($request->string('search')), '%_\\');
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'ILIKE', "%{$search}%")
                        ->orWhere('email', 'ILIKE', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->limit(15)
            ->get();

        return response()->json(['data' => $users]);
    });
});




