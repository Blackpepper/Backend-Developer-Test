<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\PriceTable;

class InventorySuppliesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $priceTable = PriceTable::find($this->itemid);
        
        return [
            'itemid' => $this->itemid,
            'quantity' => $this->quantity,
            'name' => $priceTable['name'],
            'points' => $priceTable['points'],
        ];
    }
}
