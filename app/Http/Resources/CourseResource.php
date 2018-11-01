<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'course_fee' => $this->course_fee,
            'start_date' => $this->start_date ? $this->start_date->format('Y-m-d') : '',
            'end_date' => $this->end_date ? $this->end_date->format('Y-m-d') : '',
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'venue' => $this->venue,
            'description' => $this->description,
            'info' => $this->info,
            'available_seat' => $this->available_seat,
            'created_at' => $this->created_at->format('F d, Y'),
            'computed' => [
                'member_registered_id' => $this->users->pluck('id'),
                'total' => $this->users->pluck('id')->count(),
            ],
        ];
    }
}
