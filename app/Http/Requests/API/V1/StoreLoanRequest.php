<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\Api\V1\BaseLoanRequest;

class StoreLoanRequest extends BaseLoanRequest
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
            'data.attributes.loanAmount' => 'required|numeric|min:0',
            'data.attributes.interestRate' => 'required|numeric|min:0|max:100',
            'data.attributes.loanDuration' => 'required|integer|min:1',
            'data.attributes.lenderId' => 'required|exists:users,id',
            'data.attributes.borrowerId' => 'required|exists:users,id',
        ];
    }
}
