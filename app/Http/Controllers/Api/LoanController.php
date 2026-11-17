<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Http\Resources\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $loans = Loan::with(["user","book"])
        ->when($request->filled("status"), fn ($q) => $q->where('status', $request->status))
        ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->user_id))
        ->orderByDesc('borrowed_at')
        ->paginate($request->per_page);

        return LoanResource::collection($loans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanRequest $request): LoanResource|JsonResponse
    {
        $validated = $request->validated();
        $userId = $validated['user_id'];

        $activeLoansCount = Loan::where('user_id', $userId)
            ->whereIn('status', ['active', 'overdue'])
            ->count();

        if($activeLoansCount >= 3){
            return response()->json([
                'message' => 'El usuario ya alcanzó el límite máximo de 3 préstamos activos.'
            ], 422);
        }

        $result = DB::transaction(function () use ($validated){
            $book = Book::where('id', $validated['book_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if($book->available_stock <= 0) {
                return response()->json([
                    'message' => 'No hay ejemplares disponibles de este libro para préstamo.',
                ], 422);
            }

            $loan = Loan::create($validated);

            if(in_array($loan->status ?? 'active', ['active', 'overdue'])){
                $book->decrement('available_stock');
            }

            return $loan;
        });

        if ($result instanceof JsonResponse) {
            return $result;
        }

        return (new LoanResource($result->load(['user', 'book'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        return new LoanResource($loan->load(['user','book']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoanRequest $request, Loan $loan): LoanResource
    {
        $validated = $request->validated();
        $previousStatus = $loan->status;

        DB::transaction(function () use ($loan, $validated, $previousStatus){
            if(($validated['status'] ?? null) === 'returned' && empty($validated['return_date']) && !$loan->return_date){
                $validated['return_date'] = now()->toDateString();
            }
            
            $loan->update($validated);

            if($previousStatus !== 'returned' && $loan->status === 'returned'){
                Book::where('id', $loan->book_id)->increment('available_stock');
            }
        });

        return new LoanResource($loan->load(['user','book']));
    }

    /**
     * Retornar el préstamo y reintegrar al available_stock
     */
    public function returnBook(Loan $loan): JsonResponse|LoanResource
    {
        if($loan->status === 'returned') {
            return response()->json([
                'message' => 'Este préstamo ya fue marcado como devuelto anteriormente.'
            ], 422);
        }

        $loan -> markAsReturned();

        return new LoanResource($loan->load(['user','book']));
    }
}
