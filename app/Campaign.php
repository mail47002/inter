<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'campaigns';

    public function tags()
    {
        return $this->hasMany('App\CampaignTag');
    }

    public function questions()
    {
        return $this->hasMany('App\CampaignQuestion', 'campaign_id', 'id');
    }

    public function results()
    {
        return $this->hasMany('App\CampaignResult');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function scopeRecentPublic($query)
    {
        return $query->where('active', 1)
            ->where('public', 1)
            ->orderBy('id', 'desc');
    }
}
