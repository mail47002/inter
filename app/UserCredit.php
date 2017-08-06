<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCredit extends Model
{
    protected $table = 'users_credits';

    protected $fillable = [
        'user_id', 'credits', 'description'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
