<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PostResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return [
        //     'id' => $this->id,
        //     'title' => $this->title,
        //     'cover_img' => $this->cover_img,
        //     'body' => $this->body,
        //     'status' => $this->status,
        //     'post_type_id' => $this->post_type_id,
        //     'post_category' => $this->postCategory,
        //     'event' => $this->event ? $this->event : null,
        //     'created_at' => $this->created_at->format('F d, Y h:ia'),
        //     'from_now' => $this->created_at->diffForHumans(),
        // ];
        return parent::toArray($request);
    }
}
