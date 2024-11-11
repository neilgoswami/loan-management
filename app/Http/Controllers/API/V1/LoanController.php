<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreLoanRequest;
use App\Http\Requests\API\V1\UpdateLoanRequest;
use App\Http\Resources\V1\LoanResource;
use App\Models\Loan;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return LoanResource::collection(Loan::paginate());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $loan = Loan::findOrFail($id);

            return new LoanResource($loan);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Loan cannot be found.',
                'status' => 404
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanRequest $request)
    {
        return new LoanResource(Loan::create($request->mappedAttributes()));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoanRequest $request, string $id)
    {
        try {
            $loan = Loan::findOrFail($id);

            // Check if the user is the lender of the loan being updated
            if ($request->user()->cannot('update', $loan)) {
                throw new Exception('Access denied: only the lender can update this loan.', 403);
            }

            $loan->update($request->mappedAttributes());

            return new LoanResource($loan);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Loan cannot be found.',
                'status' => 404
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $loan = Loan::findOrfail($id);

            // Check if the user is the lender of the loan being updated
            if (Auth::user()->cannot('delete', $loan)) {
                throw new Exception('Access denied: only the lender can delete this loan.', 403);
            }

            $loan->delete();

            return response()->json([
                'message' => 'Loan successfully deleted.',
                'status' => 200
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Loan cannot be found.',
                'status' => 404
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode()
            ], $e->getCode());
        }
    }
}
