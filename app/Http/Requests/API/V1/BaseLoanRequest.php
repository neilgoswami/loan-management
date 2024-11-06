<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseLoanRequest extends FormRequest
{
  public function mappedAttributes()
  {
    $attributeMap = [
      'data.attributes.loanAmount' => 'loan_amount',
      'data.attributes.interestRate' => 'interest_rate',
      'data.attributes.loanDuration' => 'loan_duration',
    ];

    $attributesToUpdate = [];
    foreach ($attributeMap as $key => $attribute) {
      if ($this->has($key)) {
        $attributesToUpdate[$attribute] = $this->input($key);
      }
    }

    return $attributesToUpdate;
  }

  public function messages(): array
  {
    return [
      'required' => 'The :attribute field is required.',
      'numeric' => 'The :attribute field must be a number.',
      'integer' => 'The :attribute field must be an integer.',
    ];
  }
}
