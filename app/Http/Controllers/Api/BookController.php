<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\InArray;

class BookController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $alloweSorts = ['title', 'publication_year', 'stock', 'available_stock'];

        $sortBy = in_array($request->query('sort_by'), $alloweSorts, true)
            ? $request->query('sort_by')
            : 'title';

        $direction = strtolower($request->query('direction', 'asc')) === 'desc'
            ? 'desc'
            : 'asc';
            
        $books = Book::with('authors')
        ->when($request->boolean('available'), fn ($q) => $q->availableStock())
        ->when($request->filled('year'), fn($q) => $q->byPublicationYear((int) $request->query('year')))
        ->when($request->filled('author'), fn($q) => $q->byAuthor($request->query('author')))
        ->when($request->filled('search'), function ($q) use ($request){
            $search = addcslashes(trim($request->string('search')), '%_\\');
            $q->where(function ($sub) use ($search) {
                $sub->where('title', 'ILIKE', "%{$search}%")
                    ->orWhere('isbn', 'ILIKE', "%{$search}%")
                    ->orWhereHas('authors', function ($authorQuery) use ($search) {
                        $authorQuery->whereRaw(
                            "(COALESCE(name, '') || ' ' || COALESCE(last_name, '') || ' ' || COALESCE(nationality, '')) ILIKE ?",
                            ["%{$search}%"]
                        );
                    });
            });
        })
        ->orderBy($sortBy, $direction)
        ->paginate($request->per_page)
        ->withQueryString();
        
        return BookResource::collection($books);
    }

    public function show(Book $book): BookResource
    {
        $book->load('authors')->loadCount('loans');
        
        return new BookResource($book);
    }

    public function store(StoreBookRequest $request): BookResource
    {
        $validated = $request->validated();

        $book = DB::transaction(function () use ($validated) {
            $validated['available_stock'] = $validated['available_stock'] ?? $validated['stock'];

            $book = Book::create($validated);

            if(!empty($validated['authors'])) {
                $book->syncAuthorsWithOrder($validated['authors']);
            }

            return $book;
        });

        return new BookResource($book->load('authors'));
    }

    public function update(UpdateBookRequest $request, Book $book): BookResource
    {
        $validated = $request->validated();

        DB::transaction(function () use ($book, $validated) {
            $book->update($validated);

            if(array_key_exists('authors', $validated)) {
                $book->syncAuthorsWithOrder($validated['authors'] ?? []);
            }
        });

        return new BookResource($book->load('authors'));
    }

    /**
     * Aquí se ejecuta el soft delete únicamente si no tiene préstamos activos
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Book $book): JsonResponse
    {
        if($book->loans()->whereIn('status', ['active', 'overdue'])->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar el libro porque tiene préstamos activos o vencidos.'
            ], 409);
        }

        $book->delete();
        return response()->json([
            'message' => 'Libro eliminado correctamente.'
        ], 200);
    }
}