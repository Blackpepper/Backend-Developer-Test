<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MartianCreateRequest extends FormRequest
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
            'gender' => [
                'required',
                'string',
                'max:6'
            ],
            'age' => [
                'required',
                'numeric'
            ]
        ];
    }
}
