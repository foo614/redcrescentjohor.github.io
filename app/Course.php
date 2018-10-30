<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name', 'course_fee', 'start_date', 'end_date', 'time_start', 'time_end', 'available_seat', 'venue', 'info', 'description'
    ];
    protected $dates = ['start_date', 'end_date', 'time_start', 'time_end'];

    /**
     * state the relationship btw model
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}