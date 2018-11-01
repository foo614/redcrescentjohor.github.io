<?php

namespace App\Http\Resources;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'title' => $this->title,
            'address' => $this->event['address'],
            'startDate' => $this->event['start'] ? $this->event['start']->format('Y-m-d H:i') : null,
            'endDate' => $this->event['end'] ? $this->event['end']->format('Y-m-d H:i') : null,
            'map_lat' => $this->event['map_lat'],
            'map_lng' => $this->event['map_lng'],
            'cover_img' => $this->cover_img,        
        ];
    }
}