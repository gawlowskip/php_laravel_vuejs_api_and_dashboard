<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Area as AreaResource;
use App\Http\Resources\Lead as LeadResource;

class Ad extends JsonResource
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
            'developer_id' => (int)$this->developer_id,
            'from_date' => (string)$this->from_date,
            'to_date' => (string)$this->to_date,
            'active' => (bool)$this->active,
            'image' => (string)$this->image,
            'external_image_url' => (string)$this->external_image_url,
            'url' => (string)$this->url,
            'price' => (float)$this->price,
            'price_lead' => (float)$this->price_lead,
            'seconds' => (float)$this->seconds,
            'areas' => AreaResource::collection($this->areas),
            'leads' => LeadResource::collection($this->leads),
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
