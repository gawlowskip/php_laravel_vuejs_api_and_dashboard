<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Lead extends JsonResource
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
            'ad_id' => (int)$this->ad_id,
            'user_id' => (string)$this->user_id,
            'full_name' => (string)$this->full_name,
            'email' => (string)$this->email,
            'clicked_on' => (string)$this->clicked_on,
            'latitude' => (float)$this->latitude,
            'longitude' => (float)$this->longitude,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
