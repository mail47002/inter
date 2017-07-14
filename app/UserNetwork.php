<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNetwork extends Model
{
    protected $table = 'users_networks';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
