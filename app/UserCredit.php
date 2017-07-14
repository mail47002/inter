<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCredit extends Model
{
    protected $table = 'users_credits';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
