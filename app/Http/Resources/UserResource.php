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
            'roles' => $this->roles->pluck('id')->toArray(),
            'branch' => $this->branch,
            'ic' => $this->ic,
            'address' => $this->address,
            'detachment' => $this->detachment,
            'membership_type_id' => $this->membership_type_id,
            'blood_type_id' => $this->blood_type_id,
            'blood_type' => $this->blood_type,
            'branch_id' => $this->branch_id,
            'created_at' => $this->created_at->format('F d, Y'),
            'profile_url' => url('/users/'.$this->id)
        ];
    }
}
