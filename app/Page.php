<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'published'];

    public function getPages()
    {
    	$pages = $this->latest('created_at')->published()->get();
    	return $pages;
    }

    public function scopePublished($query)
    {
    	// $query->where('published', '=', 1);
    }
}
