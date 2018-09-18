<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'avatar' => $this->avatar,
            'email' => $this->email,
            'contact' => $this->contact,
            'roles' => $this->roles,
            'branch' => $this->branch,
            'ic' => $this->ic,
            'created_at' => $this->created_at->format('F d, Y'),
            'profile_url' => url('/users/'.$this->id)
        ];
    }
}
