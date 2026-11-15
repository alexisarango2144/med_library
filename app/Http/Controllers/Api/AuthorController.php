<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::orderBy('name', 'asc')->paginate(2);

        return response()->json(['authors' => $authors]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $author = Author::create($request->validated());

        return response()->json(['author' => $author], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $author = Author::findOrFail($id);
        return response()->json(['author' => $author]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author): JsonResponse
    {
        $author->update($request->validated());

        return response()->json(['author' => $author], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $author = Author::findOrFail($id);
        $author->delete();

        return response()->json(['message' => 'Author deleted successfully'], 200);
    }
}
