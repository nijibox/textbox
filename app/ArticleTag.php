<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;


class ArticleTag extends Model
{
    protected $primaryKey = ['article_id', 'sort_num'];

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'sort_num', 'body',
    ];

    public function article()
    {
        return $this->belongsTo(\App\Article::class);
    }

    /**
     * タグの使用数を集計する
     * - 下書きのものは公開対象外にする
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public static function calcSummary()
    {
        return static::select('body', DB::raw('count(*) as count'))
            ->whereHas('article', function ($query) {
                $query->where('status', '!=', 'draft');
            })
            ->groupBy('body')
            ->orderBy('body', 'asc');
    }
}
