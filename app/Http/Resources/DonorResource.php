<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonorResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'contact' => $this->contact,
            'address' => $this->address,
            'map_lat' => $this->map_lat,
            'map_lng' => $this->map_lng,
            'blood_type_id' => $this->blood_type_id,
            'blood_type' => $this->blood_type,
            'created_at' => $this->created_at->format('F d, Y'),
        ];
    }
}
