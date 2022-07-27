<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'barcode' => ['required'],
            'name' => ['required'],
            'category_id' => ['required'],
            'unit_id' => ['required'],
            'purchase_price' => ['required', 'numeric'],
            'selling_price' => ['required', 'numeric'],
            'min_stock' => ['required', 'numeric'],
            'first_stock' => ['required', 'numeric'],
            'stock' => ['required', 'numeric']
        ];
    }
}
