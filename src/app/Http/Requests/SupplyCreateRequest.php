<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplyCreateRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:150'
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'points' => [
                'required',
                'numeric'
            ],
            'quantity' => [
                'required',
                'numeric'
            ],
            'status' => [
                'required',
                'numeric'
            ],
            'martian_id' => [
                'required',
                'numeric'
            ]
        ];
    }
}
