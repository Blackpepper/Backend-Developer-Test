<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MartianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $inventorysupplies = $this->whenLoaded('inventorysupplies');
        return [
            'martianid' => $this->martianid,
            'name' => $this->name,
            'age' => $this->age,
            'allow' => $this->allow,
            'inventory' => InventorySuppliesResource::collection($inventorysupplies), 
        ];
    }
}
