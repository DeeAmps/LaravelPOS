<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;



class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
    }


    public function hasAnyRole($roles)
    {
        
        if(is_array($roles)) {
            foreach($roles as $role) {
                if($this->hasRole($role)){
                    return true;
                }
            }
        }
        return false;
    }

    public function hasRole($role) 
    {
        if($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }

    public function sales()
    {
        return $this->hasMany('App\Sale');
    }
}
