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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'age' => $this->age,
            'can_trade' => $this->can_trade,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
