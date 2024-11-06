<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\Api\V1\BaseLoanRequest;

class UpdateLoanRequest extends BaseLoanRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.loanAmount' => 'numeric|min:0',
            'data.attributes.interestRate' => 'numeric|min:0|max:100',
            'data.attributes.loanDuration' => 'integer|min:1',
        ];
    }
}
