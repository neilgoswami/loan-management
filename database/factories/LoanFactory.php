<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'loan_amount' => $this->faker->numberBetween(1000, 100000),
            'interest_rate' => $this->faker->randomFloat(2, 1, 20),
            'loan_duration' => $this->faker->numberBetween(12, 120),
            'lender_id' => null,
            'borrower_id' => null,
        ];
    }
}
