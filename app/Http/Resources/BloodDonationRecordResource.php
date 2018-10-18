<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BloodDonationRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->user,
            'post_id' => $this->post_id,
            'post' => $this->post,
            'volume' => $this->volume,
            'donate_date' => $this->donate_date->format('Y-m-d'),
        ];
    }
}
