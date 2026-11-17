<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'auth.login')->name('login.view');

Route::view('/catalogo', 'books.index')->name('books.catalog');

/**
 * Ruta con lógica, pendiente crear controlador
*/
Route::get('/catalogo/{slug}', function (string $slug) {
    return view('books.show', ['slug' => $slug]);
})->name('books.show');

Route::view('/prestamos/nuevo', 'loans.create')->name('loans.create');