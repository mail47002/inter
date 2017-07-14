<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignResult extends Model
{
    protected $table = 'campaigns_results';

    public function answers()
    {
        return $this->hasMany('App\CampaignAnswer', 'result_id');
    }
}
