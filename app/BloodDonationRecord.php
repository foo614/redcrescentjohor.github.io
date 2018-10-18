<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloodDonationRecord extends Model
{
    protected $dates = ['donate_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
