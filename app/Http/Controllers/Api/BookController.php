<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;


class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('title', 'asc')->paginate(2);
        return response()->json(['books' => $books], 200);
    }

    public function show(Book $book)
    {
        return response()->json(['book' => $book], 200);
    }

    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());

        return response()->json(['book' => $book], 201);
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        return response()->json(['book' => $book], 201);
    }


    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully'], 200);
    }
}