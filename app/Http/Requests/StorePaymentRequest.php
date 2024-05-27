<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'image' => 'required',
            'payment_type' => 'required',
            'name' => 'required',
            'number' => 'required',
            'available_currency' => 'required|array',
            'available_currency.*' => 'required|in:USD,BAHT,MMK',
        ];
    }
}
