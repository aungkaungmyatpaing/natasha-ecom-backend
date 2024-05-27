<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'from_currency_unit' => 'required|in:MMK,BAHT,USD',
           'currency_rate' => 'required|numeric',
           'currency_unit' => 'required|in:MMK,BAHT,USD|different:from_currency_unit'
        ];
    }
}
