<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyFeature extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int)$this->id,
            'property_type' => (string)$this->property_type,
            'material' => (string)$this->material,
            'completion_date' => (string)$this->completion_date,
            'size' => (string)$this->size,
            'rooms_amount' => (int)$this->rooms_amount,
            'baths_amount' => (int)$this->baths_amount,
            'bedrooms_amount' => (int)$this->bedrooms_amount,
            'floors' => (int)$this->floors,
            'price' => (string)$this->price,
            'property_id' => (int)$this->property_id,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}