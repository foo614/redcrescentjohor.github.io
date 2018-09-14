<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Post extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    /**
     * Get the user Gravatar by their email address.
     *
     * @return string   
     */
    public function getAvatarAttribute()
    {
        return sprintf('https://picsum.photos/320/180/?random');
    }
}
