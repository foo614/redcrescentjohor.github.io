<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $hidden = [
        'updated_at'
    ];
    public function post()
    {
        return $this->hasOne('App\Post');
    }
}
