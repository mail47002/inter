<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'photo', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function credits()
    {
        return $this->hasMany('App\UserCredit');
    }

    public function campaigns()
    {
        return $this->hasMany('App\Campaign');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function isAdmin()
    {
        return $this->attributes['role_id'] === config('user_roles.admin');
    }
}
