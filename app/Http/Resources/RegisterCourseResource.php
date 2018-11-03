<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegisterCourseResource extends JsonResource
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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'users' => $this->users ?  $this->users->pluck('email') : null,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'available_seat' => $this->available_seat,
            'venue' => $this->venue,
            'description' => $this->description,
            'info' => $this->info,
            'created_at' => $this->created_at->format('F d, Y'),
        ];
    }
}
