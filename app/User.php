<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'avatar'
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
     * state the relationship btw  model
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
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
        return $this->hasMany(Post::class);
    }

    public function blood_donation_records(){
        return $this->hasMany(BloodDonationRecord::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function courses(){
        return $this->belongsToMany(Course::class)->withTimestamps();
    }

    public function profile()
    {
        return $this->hasOne(SocialAuth::class);
    }

    public function isAdmin()
    {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->name == 'administrator')
            {
                return true;
            }
        }

        return false;
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
