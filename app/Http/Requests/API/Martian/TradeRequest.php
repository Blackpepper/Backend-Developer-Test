<?php

namespace App\Http\Requests\API\Martian;

use Illuminate\Foundation\Http\FormRequest;

class TradeRequest extends FormRequest
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
        return [
            'supplies' => 'required|array',
            'supplies.*.id' => 'required|exists:supplies,id',
            'supplies.*.quantity' => 'required|integer',
            'trader_id' => 'required|exists:martians,id',
            'supplies_of_trader' => 'required|array',
            'supplies_of_trader.*.id' => 'required|exists:supplies,id',
            'supplies_of_trader.*.quantity' => 'required|integer',
        ];
    }
}
