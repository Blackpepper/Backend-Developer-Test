<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MartianUpdateRequest extends FormRequest
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
                'nullable',
                'string',
                'max:150'
            ],
            'gender' => [
                'nullable',
                'string',
                'max:6'
            ],
            'age' => [
                'nullable',
                'numeric'
            ],
            'can_trade' => [
                'nullable',
                'numeric'
            ]
        ];
    }
}
