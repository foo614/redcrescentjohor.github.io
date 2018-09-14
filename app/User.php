<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * state the relationship btw role model
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();;
    }

    public function membership_type()
    {
        return $this->belongsTo(MembershipType::class);
    }

    public function blood_type()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function posts(){
        return $this->hasMany('App\Post');
    }

    public function branch(){
        return $this->belongsTo('App\Branch');
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles)
    {
        if(is_array($roles))
        {
            return $this->hasAnyRole($roles) || abort(401, 'This action is Unauthorized access.');
        }
        return $this->hasRole($roles) || abort(401, 'This action is Unauthorized access.');
    }

    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyRole($roles)
    {
    return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }
}
