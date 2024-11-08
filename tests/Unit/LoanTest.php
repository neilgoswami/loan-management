<?php

namespace Tests\Unit;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase as TestsTestCase;

class LoanTest extends TestsTestCase
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
     * Test retrieving the list of loans.
     */
    public function test_list_loans(): void
    {
        // Authenticate user
        $this->authenticateUser();

        // Create loans manually to retrieve loan list
        Loan::factory()->count(5)->create();

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
     * Test creating a loan.
     */
    public function test_create_loan(): void
    {
        // Authenticate user
        $this->authenticateUser();

        // Send POST request to create a loan
        $response = $this->postJson('/api/v1/loans', [
            "data" => [
                "attributes" => [
                    "loanAmount" => 9000,
                    "interestRate" => 19.99,
                    "loanDuration" => 12
                ]
            ]
        ]);

        // Assert the response status is 201
        $response->assertStatus(201);

        // Confirm that the loan exists in the database
        $this->assertDatabaseHas('loans', ['loan_amount' => 9000]);
    }

    /**
     * Test updating an existing loan.
     */
    public function test_update_loan(): void
    {
        // Authenticate user
        $this->authenticateUser();

        // Create a loan manually for update
        $loan = Loan::factory()->create([
            'loan_amount' => 9000,
            'interest_rate' => 19.99,
            'loan_duration' => 12,
        ]);

        // Send PATCH request to update the loan
        $response = $this->patchJson("/api/v1/loans/{$loan->id}", [
            "data" => [
                "attributes" => [
                    "interestRate" => 14.99,
                ]
            ]
        ]);

        // Assert the response status is 200
        $response->assertStatus(200);

        // Confirm the database reflects the updated loan data and missing old
        $this->assertDatabaseHas('loans', ['interest_rate' => 14.99])
            ->assertDatabaseMissing('loans', ['interest_rate' => 19.99]);
    }

    /**
     * Test deleting an existing loan.
     */
    public function test_delete_loan(): void
    {
        // Authenticate user
        $this->authenticateUser();

        // Create a loan manually for delete
        $loan = Loan::factory()->create([
            'loan_amount' => 9000,
            'interest_rate' => 19.99,
            'loan_duration' => 12,
        ]);

        // Send DELETE request to delete the loan
        $response = $this->deleteJson("/api/v1/loans/{$loan->id}");

        // Assert the response status is 200
        $response->assertStatus(200);

        // Verify the loan no longer exists in the database
        $this->assertDatabaseMissing('loans', ['id' => $loan->id]);
    }
}
