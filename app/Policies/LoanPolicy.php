<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LoanPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Loan $loan): bool
    {
        // Only the lender of a loan can update the loan
        return $user->id === $loan->lender_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Loan $loan): bool
    {
        // Only the lender of a loan can delete the loan
        return $user->id === $loan->lender_id;
    }
}
