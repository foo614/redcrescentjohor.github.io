<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FundraiserResource extends JsonResource
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
            'body' => $this->body,
            'cover_img' => $this->cover_img,
            'target_amount' => $this->target_amount,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'user' => $this->user->name,
            'created_at' => $this->created_at->format('F d, Y h:ia'),
            'total_donation' => $this->payments->sum('amount')
        ];
    }
}
