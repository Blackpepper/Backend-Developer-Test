<?php

namespace App\Http\Requests\API\Martian;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMartianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
//    public function authorize()
//    {
//        return false;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // update only trade field
        if (isset($this->trade) && !isset($this->name) && !isset($this->age) && !isset($this->gender)) {
            return ['trade' => 'required|integer'];
        }
        return [
            'name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'trade' => 'required|integer'
        ];
    }
}
