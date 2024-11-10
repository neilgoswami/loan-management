<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all users to assign lenders and borrowers
        $users = User::all();

        // Generate loans
        Loan::factory()->count(25)->make()->each(function ($loan) use ($users) {
            // Select a random user as lender
            $lender = $users->random();

            // Select a user as borrower who is not the lender
            $borrower = $users->whereNotIn('id', [$lender->id])->random();

            // Assign lender and borrower to the loan
            $loan->lender_id = $lender->id;
            $loan->borrower_id = $borrower->id;

            // Save the loan with lender and borrower assigned
            $loan->save();
        });
    }
}
