<?php

namespace App\Http\Resources;

use App\Models\TradeItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class MartianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
            'gender' => $this->gender,
            'allow_trade' => ($this->allow_trade ? true : false),
            'inventory' => $this->relationLoaded('inventories') ? TradeItemResource::collection($this->inventories) : null
        ];

    }
}
