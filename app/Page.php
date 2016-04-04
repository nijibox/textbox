<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body', 'headline', 'location',
    ];

    public static function fetchHeaderPages()
    {
        return Page::where('location', 'header')->get();
    }

    public static function fetchFooterPages()
    {
        return Page::where('location', 'footer')->get();
    }
}
