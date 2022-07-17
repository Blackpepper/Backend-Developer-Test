<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TradingRequest extends FormRequest
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
            'buyer' => [
                'required',
                'array'
            ],
            'buyer.id' => [
                'required',
                'numeric'
            ],
            'seller' => [
                'required',
                'array'
            ],
            'seller.id' => [
                'required',
                'numeric'
            ],
            'seller.supplies' => [
                'required',
                'array'
            ]
        ];
    }
}
