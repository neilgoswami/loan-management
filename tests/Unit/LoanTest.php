<?php

namespace Tests\Unit;

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
        $loan = \App\Models\Loan::factory()->create([
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
}
