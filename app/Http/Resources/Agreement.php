<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Agreement extends JsonResource
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
            'developer_id' => (int)$this->developer_id,
            'from' => (string)$this->from,
            'to' => (string)$this->to,
            'type' => $this->type,
            'trial_starts_at' => $this->trial_starts_at,
            'trial_ends_at' => $this->trial_ends_at,
            'stripe_plan' => $this->stripe_plan,
            'stripe_charge' => $this->stripe_charge,
            'price' => (float)$this->price,
            'currency' => $this->currency,
            'verified' => (bool)$this->verified,
            'status' => $this->status,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'deleted_at' => $this->deleted_at ? (string)$this->deleted_at : null
        ];
    }
}
