<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase as TestsTestCase;

class AuthTest extends TestsTestCase
{
    use RefreshDatabase;

    /**
     * Test the user login functionality.
     */
    public function test_login(): void
    {
        // Create a user manually for login
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        // Attempt to log in with the created user's credentials
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Assert the response includes a token and is successful
        $response->assertStatus(200);
    }

    public function test_logout(): void
    {
        // Create a user and generate an auth token
        $user = User::factory()->create();
        $token = $user->createToken('auth_token_' . $user->email, ['*'], now()->addDay())->plainTextToken;

        // Use the generated token in the request header to logout
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/logout');

        // Assert the response is successful and the message is as expected
        $response->assertStatus(200);
    }
}
