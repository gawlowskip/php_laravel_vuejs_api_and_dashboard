<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyLocation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int)$this->id,
            'district' => (string)$this->district,
            'city' => (string)$this->city,
            'street' => (string)$this->street,
            'latitude' => (float)$this->latitude,
            'longitude' => (float)$this->longitude,
            'property_id' => (int)$this->property_id,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
