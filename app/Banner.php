<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'position_id', 'body', 'status', 'sort_order'
    ];

    // public function setSlugAttribute($value)
    // {
    //     $this->attributes['slug'] = $this->makeSlug($value);
    // }

    // protected function makeSlug($value, $extra = '')
    // {
    //     $slug = !empty($extra) ? str_slug($value . '-' . $extra) : str_slug($value);

    //     if ($this->id === null && $this->whereSlug($slug)->exists()) {
    //         return $this->makeSlug($value, $extra + 1);
    //     }

    //     return $slug;
    // }
}
