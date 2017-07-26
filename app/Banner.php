<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'position_id', 'body', 'status', 'sort_order'
    ];
}
