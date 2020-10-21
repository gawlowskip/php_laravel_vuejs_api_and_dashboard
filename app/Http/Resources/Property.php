<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Developer as DeveloperResource;
use App\Http\Resources\PropertyFeature as PropertyFeatureResource;
use App\Http\Resources\PropertyImage as PropertyImageResource;
use App\Http\Resources\PropertyVideo as PropertyVideoResource;
use App\Http\Resources\PropertyLocation as PropertyLocationResource;

class Property extends JsonResource
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
            'description' => (string)$this->description,
            'editing_hash' => (string)$this->editing_hash,
            'bricklayer' => (string)$this->bricklayer,
            'carpenter' => (string)$this->carpenter,
            'electrician' => (string)$this->electrician,
            'vvs' => (string)$this->vvs,
            'entrepreneur' => (string)$this->entrepreneur,
            'developer' => new DeveloperResource($this->developer),
            'features' => new PropertyFeatureResource($this->feature),
            'images' => PropertyImageResource::collection($this->images),
            'location' => new PropertyLocationResource($this->location),
            'videos' => PropertyVideoResource::collection($this->videos),
            'visits' => (int)$this->countVisits(),
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
        ];
    }
}
