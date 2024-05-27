<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'required',
            'price' => 'required|numeric',
            'main_currency_unit' => 'required|in:USD,MMK,BAHT',
            'description' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'stock' => 'required'

        ];
    }
}
