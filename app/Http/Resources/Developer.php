<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Developer extends JsonResource
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
            'name' => (string)$this->name,
            'last_name' => (string)$this->last_name,
            'email' => (string)$this->email,
            'phone' => (string)$this->phone,
            'architect_name' => (string)$this->architect_name,
            // 'verified' => (int)$this->verified,
            'is_developer' => (bool)$this->is_developer,
            'is_active_developer' => (bool)$this->is_active_developer,
            'facebook_id' => (string)$this->facebook_id,
            'street_1' => (string)$this->street_1,
            'street_2' => (string)$this->street_2,
            'city' => (string)$this->city,
            'postal_code' => (string)$this->postal_code,
            'latitude' => (float)$this->latitude,
            'longitude' => (float)$this->longitude,
            'cvr_number' => (string)$this->cvr_number,
            'active' => (int)$this->active,
            'count_ads' => (int)$this->ads->count(),
            'count_properties' => (int)$this->properties->count(),
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
