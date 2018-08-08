<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Get the user Gravatar by their email address.
     *
     * @return string   
     */
    public function getAvatarAttribute()
    {
        return sprintf('https://www.gravatar.com/avatar/%s?s=100', md5($this->email));
    }
}
