<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'loan',
            'id' => $this->id,
            'attributes' => [
                'loanAmount' => $this->loan_amount,
                'interestRate' => $this->interest_rate,
                'loanDuration' => $this->loan_duration,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'links' => [
                ['self' => route('loans.show', ['loan' => $this->id])]
            ]
        ];
    }
}
