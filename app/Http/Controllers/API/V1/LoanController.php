<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreLoanRequest;
use App\Http\Requests\API\V1\UpdateLoanRequest;
use App\Http\Resources\V1\LoanResource;
use App\Models\Loan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
    public function show(Loan $loan)
    {
        return new LoanResource($loan);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanRequest $request)
    {
        $model = [
            'loan_amount' => $request->input('data.attributes.loanAmount'),
            'interest_rate' => $request->input('data.attributes.interestRate'),
            'loan_duration' => $request->input('data.attributes.loanDuration'),
        ];

        return new LoanResource(Loan::create($model));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoanRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $loan = Loan::findOrfail($id);
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
        }
    }
}
