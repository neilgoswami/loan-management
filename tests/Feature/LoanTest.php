<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authenticate a user and set the Authorization header with the token.
     */
    protected function authenticateUser(): User
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token_' . $user->email, ['*'], now()->addDay())->plainTextToken;

        $this->withHeaders([
            'Authorization' => "Bearer $token"
        ]);

        return $user;
    }

    /**
     * Test retrieving the list of loans without authentication.
     */
    public function test_list_loans_without_authentication(): void
    {
        // Generate users for lenders and borrowers
        $users = User::factory()->count(10)->create();

        // Create loans manually to retrieve loan list
        Loan::factory()->count(5)->make()->each(function ($loan) use ($users) {
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

        $response = $this->getJson('/api/v1/loans');

        // Send GET request to retrieve the loans list
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes',
                        'links'
                    ]
                ],
                'links',
                'meta'
            ]);

        // Assert that there are exactly 5 loans in the "data" array
        $response->assertJsonCount(5, 'data');
    }

    /**
     * Test that an authenticated user can create a loan successfully.
     */
    public function test_create_loan_for_authenticated_user(): void
    {
        // Authenticate user
        $this->authenticateUser();

        // Create lender and borrower users
        $lender = User::factory()->create();
        $borrower = User::factory()->create();

        // Send POST request to create a loan
        $response = $this->postJson('/api/v1/loans', [
            "data" => [
                "attributes" => [
                    'lenderId' => $lender->id,
                    'borrowerId' => $borrower->id,
                    'loanAmount' => 9000,
                    'interestRate' => 19.99,
                    'loanDuration' => 12,
                ]
            ]
        ]);

        // Check response status
        $response->assertStatus(201);

        // Check that the loan is saved in the database
        $this->assertDatabaseHas('loans', [
            'loan_amount' => 9000,
            'interest_rate' => 19.99,
            'loan_duration' => 12,
            'lender_id' => $lender->id,
            'borrower_id' => $borrower->id,
        ]);
    }

    /**
     * Test that an original lender can update a loan successfully.
     */
    public function test_update_loan_as_original_lender(): void
    {
        // Authenticate user
        $user = $this->authenticateUser();

        // Create borrower user
        $borrower = User::factory()->create();

        $loan = Loan::factory()->create([
            'lender_id' => $user->id,
            'borrower_id' => $borrower->id,
            'loan_amount' => 9000,
            'interest_rate' => 19.99,
            'loan_duration' => 12,
        ]);

        // Send PATCH request to update the loan
        $response = $this->patchJson("/api/v1/loans/{$loan->id}", [
            "data" => [
                "attributes" => [
                    'interestRate' => 14.99,
                ]
            ]
        ]);

        // Check response status
        $response->assertStatus(200);

        // Check that the loan is saved in the database
        $this->assertDatabaseHas('loans', [
            'loan_amount' => 9000,
            'interest_rate' => 14.99,
            'loan_duration' => 12,
            'lender_id' => $user->id,
            'borrower_id' => $borrower->id,
        ]);
    }

    /**
     * Test that an original lender can delete a loan successfully.
     */
    public function test_delete_loan_as_original_lender(): void
    {
        // Authenticate user
        $user = $this->authenticateUser();

        // Create borrower user
        $borrower = User::factory()->create();

        $loan = Loan::factory()->create([
            'lender_id' => $user->id,
            'borrower_id' => $borrower->id,
            'loan_amount' => 9000,
            'interest_rate' => 19.99,
            'loan_duration' => 12,
        ]);

        // Send PATCH request to update the loan
        $response = $this->deleteJson("/api/v1/loans/{$loan->id}");

        // Check response status
        $response->assertStatus(200);

        // Verify the loan no longer exists in the database
        $this->assertDatabaseMissing('loans', ['id' => $loan->id]);
    }
}
