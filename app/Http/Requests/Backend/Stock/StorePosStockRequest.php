<?php

namespace App\Http\Requests\Backend\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StorePosStockRequest extends FormRequest
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
            'inventory_id'  => 'required',
            'price'         => 'required|numeric',
            'received'      => 'required|date_format:Y-m-d',
            'expiration'    => 'required|date_format:Y-m-d'
        ];
    }
}
