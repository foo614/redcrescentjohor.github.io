<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    protected $fillable = ['name'];

    public function donor(){
        return $this->hasOne('App\Donor');
    }
}
