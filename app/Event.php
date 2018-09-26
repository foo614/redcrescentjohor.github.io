<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['address', 'map_lat', 'map_lng', 'start', 'end', 'post_id' ];
    protected $dates = ['start', 'end'];
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
