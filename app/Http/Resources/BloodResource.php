<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class BloodResource extends Resource
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
            'date' => $this->created_at->diffForHumans()
        ];
    }
}
