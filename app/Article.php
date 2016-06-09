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

    public function tags()
    {
        return $this->hasMany(\App\ArticleTag::class, 'article_id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Comment::class, 'article_id');
    }

    /**
     * 紐付いているタグを、HTMLフォームに合わせるために文字列化する
     * FIXME: もうちょっと直感的な方法があるはず
     */
    public function tagsForInput()
    {
        $tags = (array)$this->tags;
        usort ( $tags, function($a, $b) {
            if ($a->sort_num == $b->sort_num) {
                return 0;
            }
            return ($a->sort_num < $b->sort_num) ? -1 : 1;
        });
        $text = '';
        foreach ($tags[0] as $tag) {
            $text .= ',' . $tag->attributes['body'];
        }
        return mb_substr($text, 1);
    }

    /**
     * 記事に紐づくタグを、まとめて差し替える
     *
     * @param array $tags
     */
    public function updateTags(array $tags)
    {
        $this->tags()->delete();
        $sortNum = 1;
        foreach ($tags as $tagBody) {
            $this->tags()->create(['sort_num' => $sortNum, 'body' => $tagBody]);
            $sortNum++;
        }
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
