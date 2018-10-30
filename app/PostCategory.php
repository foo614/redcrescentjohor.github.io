<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    public function post()
    {
        return $this->hasMany('App\Post', 'post_type_id');
    }
}
