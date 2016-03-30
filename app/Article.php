<?php

namespace App;

use Faker\Provider\Image;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id', 'title', 'body', 'status',
    ];

    /**
     * Get author object that write it.
     */
    public function author()
    {
        return $this->belongsTo(\App\User::class, 'author_id');
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public static function latestInternal()
    {
        return Article::latest('updated_at')
            ->where('status', 'internal');

    }

    /**
     * 指定したユーザーが所有者の記事を絞り込む
     *
     * @param $author User
     * @return \Illuminate\Database\Query\Builder
     */
    public static function latestAuthor($author)
    {
        return Article::where('author_id', $author->id)
            ->latest('updated_at');
    }

}
