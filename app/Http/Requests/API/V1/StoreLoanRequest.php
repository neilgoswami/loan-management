<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
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
        ];
    }

    public function messages(): array {
        return [
            'data.attributes.loanAmount' => 'The data.attributes.loanAmount field is required.',
            'data.attributes.interestRate' => 'The data.attributes.interestRate field is required.',
            'data.attributes.loanDuration' => 'The data.attributes.loanDuration field is required.',
        ];
    }
}
