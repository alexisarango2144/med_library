<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $allowedSorts = ['name', 'last_name', 'nationality', 'birthdate', 'books_count'];
        $sortBy = in_array($request->query('sort_by'), $allowedSorts, true)
            ? $request->query('sort_by')
            : 'last_name';
        $direction = strtolower($request->query('direction', 'asc')) === 'desc'
            ? 'desc'
            : 'asc';

        $authors = Author::withCount('books')
            ->when($request->filled('search'), function ($query) use ($request){
                $search = addcslashes(trim($request->string('search')), '%_\\');
                
                $query->whereRaw(
                    "(COALESCE(name, '') || ' ' || COALESCE(last_name, '') || ' ' || COALESCE(nationality, '')) ILIKE ?",
                    ["%{$search}%"]
                );
            })
            ->orderBy($sortBy, $direction)
            ->when($sortBy === 'last_name', fn ($q) => $q->orderBy('name', $direction))
            ->when($sortBy === 'name', fn ($q) => $q->orderBy('last_name', $direction))
            ->paginate($request->per_page)
            ->withQueryString();

        return AuthorResource::collection($authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $author = Author::create($request->validated());

        return (new AuthorResource($author))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author): AuthorResource
    {
        $author->load(['books' => function ($query) {
            $query->orderByPivot('author_order', 'asc');
        }]);

        return new AuthorResource($author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author): AuthorResource
    {
        $author->update($request->validated());

        return new AuthorResource($author);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author): JsonResponse
    {
        try {
            $author->delete();

            return response()->json([
                'message' => 'Autor eliminado con éxito.'
            ], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                return response()->json([
                    'message' => 'No se puede eliminar el autor porque tiene libros asociados.'
                ], 409);
            }

            throw $e;
        }
    }
}
