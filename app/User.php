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
        'name', 'email', 'password',
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

    public function isAdmin()
    {
        return $this->attributes['type'] == config('user_types.admin');
    }
}
