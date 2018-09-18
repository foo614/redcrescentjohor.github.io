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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'body' => $this->body,
            'status' => $this->status,
            'post_type_id' => $this->post_type_id,
            // 'post_category' => ['name'=>$this->postCategory->name],
            'post_category' => $this->postCategory,
            'event' => $this->event,
            'created_at' => $this->created_at->format('F d, Y h:ia')
        ];
    }
    // public function with($request) {
    //     return [
    //         'links' => [
    //             'self' => '../posts'
    //         ]
    //     ];
    // }
}
